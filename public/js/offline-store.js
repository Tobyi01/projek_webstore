(() => {
    const databaseName = 'nesia-store-offline';
    const databaseVersion = 1;
    const endpoint = '/offline-transactions/sync';

    function openDatabase() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(databaseName, databaseVersion);
            request.onupgradeneeded = () => {
                const database = request.result;
                if (!database.objectStoreNames.contains('device')) {
                    database.createObjectStore('device', { keyPath: 'key' });
                }
                if (!database.objectStoreNames.contains('pending')) {
                    database.createObjectStore('pending', { keyPath: 'transaction_number' });
                }
            };
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }

    async function withStore(name, mode, action) {
        const database = await openDatabase();
        return new Promise((resolve, reject) => {
            const transaction = database.transaction(name, mode);
            const request = action(transaction.objectStore(name));
            let result;
            request.onsuccess = () => { result = request.result; };
            request.onerror = () => reject(request.error);
            transaction.oncomplete = () => {
                database.close();
                resolve(result);
            };
            transaction.onerror = () => {
                database.close();
                reject(transaction.error);
            };
        });
    }

    async function saveSnapshot(snapshot) {
        await withStore('device', 'readwrite', store => store.put({ key: 'snapshot', ...snapshot }));
    }

    async function getSnapshot() {
        return withStore('device', 'readonly', store => store.get('snapshot'));
    }

    async function queueTransaction(sale) {
        await withStore('pending', 'readwrite', store => store.put(sale));
    }

    async function getQueue() {
        return withStore('pending', 'readonly', store => store.getAll());
    }

    async function removeQueuedTransaction(number) {
        await withStore('pending', 'readwrite', store => store.delete(number));
    }

    async function clearDeviceData() {
        await Promise.all([
            withStore('device', 'readwrite', store => store.clear()),
            withStore('pending', 'readwrite', store => store.clear()),
        ]);
    }

    async function syncQueue() {
        if (!navigator.onLine) {
            return { synced: 0, pending: (await getQueue()).length };
        }

        const snapshot = await getSnapshot();
        const queue = await getQueue();
        let synced = 0;
        for (const sale of queue) {
            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': snapshot?.csrfToken || '',
                    },
                    body: JSON.stringify(sale),
                });
                if (!response.ok) {
                    if (response.status === 401 || response.status === 419) {
                        break;
                    }
                    const error = await response.json().catch(() => ({}));
                    if (response.status !== 409) {
                        break;
                    }
                    throw new Error(error.message || 'Konflik sinkronisasi transaksi.');
                }
                await removeQueuedTransaction(sale.transaction_number);
                synced++;
            } catch {
                break;
            }
        }

        return { synced, pending: (await getQueue()).length };
    }

    window.NesiaOffline = {
        clearDeviceData,
        getQueue,
        getSnapshot,
        queueTransaction,
        saveSnapshot,
        syncQueue,
    };

    window.addEventListener('online', () => {
        syncQueue().then(result => window.dispatchEvent(new CustomEvent('offline-queue-updated', { detail: result })));
    });
})();

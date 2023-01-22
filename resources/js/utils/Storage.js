class ClientStorage {
    static storage = window.localStorage;

    static get(key = "") {
        const value = this.storage.getItem(key) || null;
        return JSON.parse(value);
    }

    static set(key, value) {
        this.storage.setItem(key, JSON.stringify(value));
    }

    static remove(key) {
        this.storage.removeItem(key);
    }
}

export default ClientStorage;

class BaseException {
    static errorCode = 400;
    static messages = [];

    static setAttributes = function (obj) {
        Object.keys(obj).forEach((key) => {
            this[key] = obj[key];
        });
    };
    static getMessage() {
        return this.messages[0];
    }

    static getMessages() {
        return this.messages;
    }
}

export default BaseException;

import BaseException from "./base-exception";

class Validation extends BaseException {
    static errorCode = 422;

    static getMessages() {
        const messages = this.messages;
        let errorMessages = {};
        Object.keys(messages).forEach((key) => {
            errorMessages[key] = messages[key];
        });

        return errorMessages;
    }
}

export default Validation;

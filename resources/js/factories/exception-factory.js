import Unauthorized from "~/exceptions/unauthorized";
import Validation from "~/exceptions/validation";
import BadRequest from "~/exceptions/bad-request";
import UnhandledException from "~/exceptions/unhandled";

class ExceptionFactory {
    static make(options) {
        const response = options?.response || {};
        const { data } = response;

        const errCode = response.status;
        const exceptionClsMapping = {
            422: Validation,
            401: Unauthorized,
            400: BadRequest,
        };
        const exception = exceptionClsMapping[errCode] || UnhandledException;

        exception.setAttributes({
            messages: data.errors || this.formMessages(data.message),
            data: response,
        });
        return exception;
    }

    static formMessages(messages = []) {
        if (typeof messages === "string") {
            return [messages];
        }

        return messages;
    }
}

export default ExceptionFactory;

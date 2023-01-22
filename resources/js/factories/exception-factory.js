import Unauthorized from "~/exceptions/unauthorized";
// import ValidationException from "~/Infrastructure/Exception/ValidationException";
// import UnhandledException from "~/Infrastructure/Exception/UnhandledException";
import BadRequest from "~/exceptions/bad-request";
import UnhandledException from "~/exceptions/unhandled";

class ExceptionFactory {
    static make(options) {
        const response = options?.response || {};
        const { data } = response;

        const errCode = response.status;
        const exceptionClsMapping = {
            // 422: ValidationException,
            401: Unauthorized,
            400: BadRequest,
        };
        const exception = exceptionClsMapping[errCode] || UnhandledException;

        exception.setAttributes({
            messages: this.formMessages(data.message),
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

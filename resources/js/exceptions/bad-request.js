import BaseException from "./base-exception";

class BadRequest extends BaseException {
    static errorCode = 422;
}

export default BadRequest;

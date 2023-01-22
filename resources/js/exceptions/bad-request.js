import BaseException from "./base-exception";

class BadRequest extends BaseException {
    static errorCode = 400;
}

export default BadRequest;

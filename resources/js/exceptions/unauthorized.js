import BaseException from "./base-exception";

class UnauthorizedException extends BaseException {
    static errorCode = 401;
}

export default UnauthorizedException;

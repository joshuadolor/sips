const errorMap = new Map([
    [
        401,
        (e) => {
            //handle general errors
            // const message =
            //     e?.response?.data?.error?.message ||
            //     'An error occured please contact the admin';
            // if(window.Store) {
            //   Store.dispatch('session/setAccessToken', null);
            // }

            // if(mainApp) {
            //   mainApp.$root.$emit('showSnackbar', message);
            //   window.location.href='/portal'
            // }

            window.location.reload();
        },
    ],
    [
        422,
        (e) => {
            //handle specific errors
            //can be item  by item (display per field)
            if (e?.response?.data?.error?.type === "MongoError") {
                const message = e?.response?.data?.error?.message;
                if (window.mainApp) {
                    mainApp.$root.$emit("showSnackbar", message);
                }
                return message;
            }

            throw e;
        },
    ],
    [
        503,
        (e) => {
            const message =
                e?.response?.data?.error?.message || "System is on maintenance";
            mainApp.$root.$emit("showSnackbar", message);
        },
    ],
]);

const handler = (error) => {
    const code = error?.response.status;

    if (errorMap.has(code)) {
        errorMap.get(code)(error);
        return;
    }

    throw error;
};

export default handler;

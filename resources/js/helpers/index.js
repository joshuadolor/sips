const isUndefined = function(test) {
    return typeof test === 'undefined';
};

const isEmpty = (n) => {
    return isUndefined(n) || n === '' || n === null || n === 0;
};

function isObject(item) {
    return item && typeof item === 'object' && !Array.isArray(item);
}

const mergeDeep = (target, source) => {
    let output = Object.assign({}, target);
    if (isObject(target) && isObject(source)) {
        Object.keys(source).forEach((key) => {
            if (isObject(source[key])) {
                if (!(key in target))
                    Object.assign(output, {
                        [key]: source[key],
                    });
                else output[key] = mergeDeep(target[key], source[key]);
            } else {
                Object.assign(output, {
                    [key]: source[key],
                });
            }
        });
    }
    return output;
};

const downloadFile = (path) => {
    const a = document.createElement('a');
    document.body.appendChild(a);
    a.href = path;
    a.download = '';
    a.click();
};

export { mergeDeep, isEmpty, downloadFile };

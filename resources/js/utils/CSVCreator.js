export default class CSVCreator {
    constructor(data) {
        this._headers = data.headers || [];
        this._displayHeaders = data.displayHeaders || [];
        this._separator = data.separator || ",";
        this._data = data.data || [];
    }

    get displayHeaders() {
        if (this._displayHeaders.length === 0) {
            const firstElement = this._data[0];

            if (firstElement) {
                return Object.keys(firstElement.keys()).map(
                    (dHeader) => `"${dHeader}"`
                );
            }

            new Error();
        }

        return this._displayHeaders.map((dHeader) => `"${dHeader}"`);
    }

    get headers() {
        if (this._headers.length === 0) {
            const firstElement = this._data[0];

            if (firstElement) {
                return Object.keys(firstElement.keys());
            }

            new Error();
        }

        return this._headers;
    }

    get data() {
        return this._data.map((d) => {
            return this.headers.map((header) => `"${d[header]}"`);
        });
    }

    get separator() {
        return this._separator;
    }

    export(name = "site_report") {
        let csvData = "data:text/csv;charset=utf-8,";
        csvData += this.displayHeaders.join(this.separator) + "\n";

        csvData = this.data.reduce((a, b) => {
            return (a += b.join(this.separator) + "\n");
        }, csvData);

        const encodedUri = encodeURI(csvData);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("style", "display:none");
        link.setAttribute("download", name);
        document.body.appendChild(link); // Required for FF
        link.click();
    }
}

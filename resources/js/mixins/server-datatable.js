export default {
    data() {
        return {
            tableHeaders: [],
            search: "",
            items: [],
            isFetching: true,
            pagination: {
                total: 0,
                total_pages: 0,
            },
            page: 1,
            perPage: 10,
            orderBy: "-created_at",
            attrMap: {},
            debounceTimer: null,
            delayQuery: 1500,
        };
    },
    methods: {
        setOrderBy(options) {
            const { sortBy, sortDesc } = options;
            const columns = sortBy.map((column, idx) => {
                const colName = this.attrMap[column] ?? column;
                return `${sortDesc[idx] ? "-" : ""}${colName}`;
            });

            //temporary single sort
            this.orderBy = columns[0] ?? "";
        },
        getDisplayColName(orderBy) {
            orderBy = this.isDescColName(orderBy) ? orderBy.substr(1) : orderBy;
            const colName = Object.keys(this.attrMap).find(
                (key) => this.attrMap[key] === orderBy
            );
            return colName ?? orderBy;
        },
        isDescColName(orderBy = "randomcolumn") {
            return orderBy[0] === "-";
        },
        async service() {
            throw `${this.$options.name}: service not set`;
        },
        async updateData() {
            this.isFetching = true;

            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(async () => {
                await this.fetch();
            }, this.delayQuery);
        },
        async fetch() {
            this.isFetching = true;

            const filterValues = {};

            if (!!this.orderBy) {
                filterValues.order_by = this.orderBy;
            }

            const query = {
                page: this.page,
                per_page: this.perPage,
                search: this.search,
                ...filterValues,
            };

            const { data, pagination = {} } = await this.service(query);

            this.pagination = pagination;
            this.items = data;
            this.isFetching = false;
        },
    },
    computed: {
        paginationRelated() {
            return [this.page, this.perPage, this.search, this.orderBy];
        },
        tableData() {
            return {
                items: this.items,
                headers: this.tableHeaders,
                page: this.page,
                loading: this.isFetching,
                "disable-filtering": this.isFetching,
                "disable-pagination": this.isFetching,
                "disable-sort": this.isFetching,
                "server-items-length": this.pagination.total_pages,
                "items-per-page": this.perPage,
                "disable-pagination": true,
                "footer-props": {
                    "show-current-page": true,
                    pagination: {
                        pageCount: this.pagination.total_pages,
                        itemsLength: this.pagination.total,
                        page: this.page,
                        itemsPerPage: this.perPage,
                        pageStart: this.page * this.perPage - this.perPage,
                        pageStop: this.page * this.perPage,
                    },
                    "items-per-page-options": [5, 10, 20],
                },
                "sort-by": [this.getDisplayColName(this.orderBy)],
                "sort-desc": [this.isDescColName(this.orderBy)],
            };
        },
    },
    watch: {
        paginationRelated: {
            immediate: true,
            handler() {
                this.updateData();
            },
        },
    },
};

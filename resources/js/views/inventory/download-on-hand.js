import CSVCreator from "~/utils/CSVCreator";
import { format } from "date-fns";
import Service from "~/services/ProductMovementService";

export default {
    methods: {
        async downloadOnHand() {
            const data = await Service.onHand();
            const headers = [
                "Item Code",
                "Item Name",
                "Quantity on hand",
                "Quantity Sold",
                "Quantity Received",
                "Quantity Transfered",
                "Balance",
            ];

            const csv = new CSVCreator({
                headers,
                data,
                displayHeaders: headers,
            });

            const date = format(new Date(), "Y_MM_dd_HH_mm_ss");

            const fileName = this.resourceName + "(on hand)_" + date;
            csv.export(fileName);
        },
    },
};

import Vue from 'vue';
import 'babel-polyfill'; // TODO: 削除
import {EventBus} from '../Util/EventBus';
import Loading from '../Util/Component/Loading.vue';
import SimpleModal from '../Util/Component/SimpleModal.vue';
import SubmitDelete from '../Util/Mixin/SubmitDelete';
import axios from "../Util/axios";
import YubinBango from '../Util/Mixin/YubinbangoCore';

Vue.component('v-loading', Loading);
new Vue({el: '#js-loading-area'});

Vue.component('v-simple-modal', SimpleModal);
new Vue({el: '#js-simple-modal-area'});

const ADDRESS_OBJERCT = {id: '', nameLast: '', nameFirst: '', corporateName: '', departmentName: '', tel: '', zipCode: '', prefecture: '', city: '', address1: '', address2: '', sort: 0, isValid: 1,
};

const vueData = {
    isCreate: null,
    customerId: '',
    deliveryAdds: [],
    senderAdds: [],
};

new Vue({
    el: '#js-mainArea',
    delimiters: ['${', '}'],
    mixins: [SubmitDelete, YubinBango],
    data: vueData,
    mounted: async function() {
        this.isCreate = this.$refs.form.dataset.customerId == '0';
        this.customerId = this.$refs.form.dataset.customerId;
        if(!this.isCreate) {
            await this.findDeliveryAndSenderInfo();
        }
    },
    methods: {
        async findDeliveryAndSenderInfo() {
            const url = Routing.generate('app_common_cstmr_mst_find_delivery_sender_info', {
                id: this.customerId,
            });
            const res = await axios.get(url);
            this.deliveryAdds = res.data.deliveryAdds.sort((a, b) => {
                return a.sort - b.sort;
            });
            this.senderAdds = res.data.senderAdds.sort((a, b) => {
                return a.sort - b.sort;
            });
        },
        removeDeliveryAddress(idx) {
            this.deliveryAdds.splice(idx, 1);
        },
        addDeliveryAddress() {
            this.deliveryAdds.push(Object.assign({}, ADDRESS_OBJERCT));
        },
        removeSenderAddress(idx) {
            this.senderAdds.splice(idx, 1);
        },
        addSenderAddress() {
            this.senderAdds.push(Object.assign({}, ADDRESS_OBJERCT));
        },
        async submitEdit() {
            this.removeEmptyData();
            if(this.checkInputtedAddress()) {
                const form = this.$refs.form;
                const formData = new FormData(form);
                formData.append('deliveryAdds', JSON.stringify(this.deliveryAdds));
                formData.append('senderAdds', JSON.stringify(this.senderAdds));
                formData.append('isAllOrg', this.$refs.form.dataset.isAllOrg);
                const url = Routing.generate('app_common_cstmr_mst_edit_submit', {
                    id: this.customerId,
                    version: this.$refs.form.dataset.version
                });
                const res = await axios.post(url, formData);
                if (res.data.success) {
                    location.href = res.data.url;
                } else {
                    EventBus.$emit('simple-modal_alert', 'エラー', res.data.errorMessages);
                }
            }
        },
        async submitCreate() {
            this.removeEmptyData();
            if(this.checkInputtedAddress()) {
                const form = this.$refs.form;
                const formData = new FormData(form);
                formData.append('deliveryAdds', JSON.stringify(this.deliveryAdds));
                formData.append('senderAdds', JSON.stringify(this.senderAdds));
                const url = Routing.generate('app_common_cstmr_mst_create_submit');
                const res = await axios.post(url, formData);
                if (res.data.success) {
                    location.href = res.data.url;
                } else {
                    EventBus.$emit('simple-modal_alert', 'エラー', res.data.errorMessages);
                }
            }
        },
        checkInputtedAddress() {
            let count = 0;
            this.deliveryAdds.forEach(d => {
                if(!d.nameLast) {
                    EventBus.$emit('simple-modal_alert', 'エラー', ['配送先に姓が設定されていません。']);
                    ++count;
                    return false;
                }
            });
            this.senderAdds.forEach(s => {
                if(!s.nameLast) {
                    EventBus.$emit('simple-modal_alert', 'エラー', ['送り主に姓が設定されていません。']);
                    ++count;
                    return false;
                }
            });
            if(count === 0) return true;
        },
        removeEmptyData() {
            const modifiedDeliveryAdds = [];
            this.deliveryAdds.forEach((d, idx) => {
                if(!Object.entries(d).every(([key, value]) => value == '')) {
                    modifiedDeliveryAdds.push(d);
                }
            });
            const modifiedSenderAdds = [];
            this.senderAdds.forEach((s, idx) => {
                if(!Object.entries(s).every(([key, value]) => value == '')) {
                    modifiedSenderAdds.push(s);
                }
            });
            this.deliveryAdds = modifiedDeliveryAdds;
            this.senderAdds = modifiedSenderAdds;
        }
        ,
        addClassToRotate(event) {
            if(event.target.classList.contains('open_label_toggle')) {
                event.target.classList.remove('open_label_toggle');
            } else {
                event.target.classList.add('open_label_toggle');
            }
        },
        completeDeliveryAddress(event, idx) {
            this.constructor(event.target.value, addr => {
                this.deliveryAdds[idx].prefecture = addr.region;
                this.deliveryAdds[idx].city = addr.locality;
                this.deliveryAdds[idx].address1 = addr.street;
                this.deliveryAdds[idx].address2 = addr.extended;
            })
        },
        completeSenderAddress(event, idx) {
            this.constructor(event.target.value, addr => {
                this.senderAdds[idx].prefecture = addr.region;
                this.senderAdds[idx].city = addr.locality;
                this.senderAdds[idx].address1 = addr.street;
                this.senderAdds[idx].address2 = addr.extended;
            })
        }
    },
});
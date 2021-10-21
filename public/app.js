"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[260],{347:(t,a,e)=>{var s=e(538),i=e(19),o=e(669),r=e.n(o);const n=[{path:"/",redirect:"/receipts"},{path:"/receipts",name:"receipts",component:e(855).Z},{path:"/receipts/:id",name:"receipt",component:e(110).Z}];var l=e(345),c=(e(734),document.head.querySelector('meta[name="csrf-token"]'));r().defaults.headers.common["X-Requested-With"]="XMLHttpRequest",c&&(r().defaults.headers.common["X-CSRF-TOKEN"]=c.content),s.default.use(i.ZPm),s.default.use(l.Z),s.default.component("pagination",e(606)),s.default.prototype.$http=r().create(),window.FiscalRegistrar.basePath="/"+window.FiscalRegistrar.path;var p=window.FiscalRegistrar.basePath+"/";""!==window.FiscalRegistrar.path&&"/"!==window.FiscalRegistrar.path||(p="/",window.FiscalRegistrar.basePath="");var m=new l.Z({routes:n,mode:"history",base:p});new s.default({el:"#fiscal-registrar",router:m,data:{message:"Welcome to Fiscal Registrar home page!"}})},67:()=>{},110:(t,a,e)=>{e.d(a,{Z:()=>i});const s={data:function(){return{ready:!1,receipt:{}}},mounted:function(){this.loadReceipt(this.$route.params.id),document.title="Fiscal Registrar - Receipt"},methods:{loadReceipt:function(t){var a=this;this.ready=!1,this.$http.get(FiscalRegistrar.basePath+"/api/v1/receipts/"+t).then((function(t){a.receipt=t.data,a.ready=!0}))},saveReceipt:function(){this.$http.put(FiscalRegistrar.basePath+"/api/v1/receipts/"+this.receipt.id,this.receipt)}}};const i=(0,e(900).Z)(s,(function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",[t.ready?e("form",{on:{keyup:function(a){return!a.type.indexOf("key")&&t._k(a.keyCode,"enter",13,a.key,"Enter")?null:t.saveReceipt.apply(null,arguments)}}},[e("div",{staticClass:"accordion mb-3",attrs:{id:"receiptAccordion"}},[e("div",{staticClass:"accordion-item"},[t._m(0),t._v(" "),e("div",{staticClass:"accordion-collapse collapse show",attrs:{id:"clientDataCollapse","aria-labelledby":"clientDataHeading"}},[e("div",{staticClass:"accordion-body row"},[e("div",{staticClass:"col-sm-auto mb-3"},[e("label",{staticClass:"form-label",attrs:{for:"clientEmail"}},[t._v("Электронный адрес")]),t._v(" "),e("input",{directives:[{name:"model",rawName:"v-model",value:t.receipt.data.client.email,expression:"receipt.data.client.email"}],staticClass:"form-control form-control-sm",attrs:{type:"email",id:"clientEmail",placeholder:"user@domain.com"},domProps:{value:t.receipt.data.client.email},on:{input:function(a){a.target.composing||t.$set(t.receipt.data.client,"email",a.target.value)}}})]),t._v(" "),e("div",{staticClass:"col-sm-auto mb-3"},[e("label",{staticClass:"form-label",attrs:{for:"clientPhone"}},[t._v("Телефон")]),t._v(" "),e("input",{directives:[{name:"model",rawName:"v-model",value:t.receipt.data.client.phone,expression:"receipt.data.client.phone"}],staticClass:"form-control form-control-sm",attrs:{type:"tel",id:"clientPhone",placeholder:"+79001234567"},domProps:{value:t.receipt.data.client.phone},on:{input:function(a){a.target.composing||t.$set(t.receipt.data.client,"phone",a.target.value)}}})]),t._v(" "),e("div",{staticClass:"col-sm-auto mb-3"},[e("label",{staticClass:"form-label",attrs:{for:"clientName"}},[t._v("Наименование")]),t._v(" "),e("input",{directives:[{name:"model",rawName:"v-model",value:t.receipt.data.client.name,expression:"receipt.data.client.name"}],staticClass:"form-control form-control-sm",attrs:{type:"text",id:"clientName",placeholder:"Иван Иванович Иванов"},domProps:{value:t.receipt.data.client.name},on:{input:function(a){a.target.composing||t.$set(t.receipt.data.client,"name",a.target.value)}}})]),t._v(" "),e("div",{staticClass:"col-sm-auto mb-3"},[e("label",{staticClass:"form-label",attrs:{for:"clientInn"}},[t._v("ИНН")]),t._v(" "),e("input",{directives:[{name:"model",rawName:"v-model",value:t.receipt.data.client.inn,expression:"receipt.data.client.inn"}],staticClass:"form-control form-control-sm",attrs:{type:"text",id:"clientInn",placeholder:"1234567890",pattern:"\\d{10}|\\d{12}",maxlength:"12"},domProps:{value:t.receipt.data.client.inn},on:{input:function(a){a.target.composing||t.$set(t.receipt.data.client,"inn",a.target.value)}}})])])])]),t._v(" "),t._m(1),t._v(" "),e("div",{staticClass:"accordion-item"},[t._m(2),t._v(" "),e("div",{staticClass:"accordion-collapse collapse show",attrs:{id:"itemDataCollapse","aria-labelledby":"itemDataHeading"}},[e("div",{staticClass:"accordion-body"},t._l(t.receipt.data.items,(function(a,s){return e("div",{staticClass:"row"},[e("div",{staticClass:"col-sm-4 mb-3"},[e("label",{staticClass:"form-label",attrs:{for:"item"+(s+1)+"Name"}},[t._v("Наименование")]),t._v(" "),e("input",{directives:[{name:"model",rawName:"v-model",value:a.name,expression:"item.name"}],staticClass:"form-control form-control-sm",attrs:{type:"text",id:"item"+(s+1)+"Name"},domProps:{value:a.name},on:{input:function(e){e.target.composing||t.$set(a,"name",e.target.value)}}})]),t._v(" "),e("div",{staticClass:"col-sm-2 mb-3"},[e("label",{staticClass:"form-label",attrs:{for:"item"+(s+1)+"Price"}},[t._v("Цена")]),t._v(" "),e("input",{directives:[{name:"model",rawName:"v-model",value:a.price,expression:"item.price"}],staticClass:"form-control form-control-sm",attrs:{type:"number",id:"item"+(s+1)+"Price"},domProps:{value:a.price},on:{input:function(e){e.target.composing||t.$set(a,"price",e.target.value)}}})]),t._v(" "),e("div",{staticClass:"col-sm-2 mb-3"},[e("label",{staticClass:"form-label",attrs:{for:"item"+(s+1)+"Quantity"}},[t._v("Кол-во")]),t._v(" "),e("input",{directives:[{name:"model",rawName:"v-model",value:a.quantity,expression:"item.quantity"}],staticClass:"form-control form-control-sm",attrs:{type:"number",id:"item"+(s+1)+"Quantity"},domProps:{value:a.quantity},on:{input:function(e){e.target.composing||t.$set(a,"quantity",e.target.value)}}})]),t._v(" "),e("div",{staticClass:"col-sm-2 mb-3"},[e("label",{staticClass:"form-label",attrs:{for:"item"+(s+1)+"Sum"}},[t._v("Сумма")]),t._v(" "),e("input",{directives:[{name:"model",rawName:"v-model",value:a.price,expression:"item.price"}],staticClass:"form-control form-control-sm",attrs:{type:"number",id:"item"+(s+1)+"Sum"},domProps:{value:a.price},on:{input:function(e){e.target.composing||t.$set(a,"price",e.target.value)}}})]),t._v(" "),e("div",{staticClass:"col-sm-2 mb-3"},[e("label",{staticClass:"form-label",attrs:{for:"item"+(s+1)+"MeasurementUnit"}},[t._v("Ед. изм.")]),t._v(" "),e("input",{directives:[{name:"model",rawName:"v-model",value:a.measurement_unit,expression:"item.measurement_unit"}],staticClass:"form-control form-control-sm",attrs:{type:"text",id:"item"+(s+1)+"MeasurementUnit"},domProps:{value:a.measurement_unit},on:{input:function(e){e.target.composing||t.$set(a,"measurement_unit",e.target.value)}}})]),t._v(" "),e("div",{staticClass:"col-sm-auto mb-3"},[e("label",{staticClass:"form-label",attrs:{for:"item"+(s+1)+"PaymentMethod"}},[t._v("Способ расчета")]),t._v(" "),e("select",{directives:[{name:"model",rawName:"v-model",value:a.payment_method,expression:"item.payment_method"}],staticClass:"form-select form-select-sm",attrs:{id:"item"+(s+1)+"PaymentMethod"},on:{change:function(e){var s=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.$set(a,"payment_method",e.target.multiple?s:s[0])}}},[e("option",{attrs:{value:"full_prepayment"}},[t._v("предоплата 100%")]),t._v(" "),e("option",{attrs:{value:"prepayment"}},[t._v("предоплата")]),t._v(" "),e("option",{attrs:{value:"advance"}},[t._v("аванс")]),t._v(" "),e("option",{attrs:{value:"full_payment"}},[t._v("полный расчет")]),t._v(" "),e("option",{attrs:{value:"partial_payment"}},[t._v("частичный расчет и кредит")]),t._v(" "),e("option",{attrs:{value:"credit"}},[t._v("передача в кредит")]),t._v(" "),e("option",{attrs:{value:"credit_payment"}},[t._v("оплата кредита")])])]),t._v(" "),e("div",{staticClass:"col-sm-auto mb-3"},[e("label",{staticClass:"form-label",attrs:{for:"item"+(s+1)+"PaymentObject"}},[t._v("Предмет расчета")]),t._v(" "),e("select",{directives:[{name:"model",rawName:"v-model",value:a.payment_object,expression:"item.payment_object"}],staticClass:"form-select form-select-sm",attrs:{id:"item"+(s+1)+"PaymentObject"},on:{change:function(e){var s=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.$set(a,"payment_object",e.target.multiple?s:s[0])}}},[e("option",{attrs:{value:"commodity"}},[t._v("товар")]),t._v(" "),e("option",{attrs:{value:"excise"}},[t._v("подакцизный товар")]),t._v(" "),e("option",{attrs:{value:"job"}},[t._v("работа")]),t._v(" "),e("option",{attrs:{value:"service"}},[t._v("услуга")]),t._v(" "),e("option",{attrs:{value:"gambling_bet"}},[t._v("ставка азартной игры")]),t._v(" "),e("option",{attrs:{value:"gambling_prize"}},[t._v("выигрыш азартной игры")]),t._v(" "),e("option",{attrs:{value:"lottery"}},[t._v("лотерейный билет")]),t._v(" "),e("option",{attrs:{value:"lottery_prize"}},[t._v("выигрыш лотереи")]),t._v(" "),e("option",{attrs:{value:"intellectual_activity"}},[t._v("предоставление результатов интеллектуальной деятельности")]),t._v(" "),e("option",{attrs:{value:"payment"}},[t._v("платеж")]),t._v(" "),e("option",{attrs:{value:"agent_commission"}},[t._v("агентское вознаграждение")]),t._v(" "),e("option",{attrs:{value:"award"}},[t._v("взнос/штраф/вознаграждение/бонус")]),t._v(" "),e("option",{attrs:{value:"composite"}},[t._v("составной предмет расчета")]),t._v(" "),e("option",{attrs:{value:"another"}},[t._v("иной предмет расчета")]),t._v(" "),e("option",{attrs:{value:"property_right"}},[t._v("имущественное право")]),t._v(" "),e("option",{attrs:{value:"non-operating_gain"}},[t._v("внереализационный доход")]),t._v(" "),e("option",{attrs:{value:"insurance_premium"}},[t._v("страховые взносы")]),t._v(" "),e("option",{attrs:{value:"sales_tax"}},[t._v("торговый сбор")]),t._v(" "),e("option",{attrs:{value:"resort_fee"}},[t._v("курортный сбор")]),t._v(" "),e("option",{attrs:{value:"deposit"}},[t._v("залог")]),t._v(" "),e("option",{attrs:{value:"expense"}},[t._v("расход")]),t._v(" "),e("option",{attrs:{value:"pension_insurance_ip"}},[t._v("взносы на ОПС ИП")]),t._v(" "),e("option",{attrs:{value:"pension_insurance"}},[t._v("взносы на ОПС")]),t._v(" "),e("option",{attrs:{value:"medical_insurance_ip"}},[t._v("взносы на ОМС ИП")]),t._v(" "),e("option",{attrs:{value:"medical_insurance"}},[t._v("взносы на ОМС")]),t._v(" "),e("option",{attrs:{value:"social_insurance"}},[t._v("взносы на ОСС")]),t._v(" "),e("option",{attrs:{value:"casino_payment"}},[t._v("платеж казино")])])])])})),0)])]),t._v(" "),e("div",{staticClass:"accordion-item"},[t._m(3),t._v(" "),e("div",{staticClass:"accordion-collapse collapse show",attrs:{id:"paymentDataCollapse","aria-labelledby":"paymentDataHeading"}},[e("div",{staticClass:"accordion-body row"},[e("div",{staticClass:"col-sm-auto mb-3"},[e("label",{staticClass:"form-label",attrs:{for:"paymentCash"}},[t._v("Наличными")]),t._v(" "),e("input",{directives:[{name:"model",rawName:"v-model",value:t.receipt.data.payments.cash,expression:"receipt.data.payments.cash"}],staticClass:"form-control form-control-sm",attrs:{type:"number",id:"paymentCash",placeholder:"0"},domProps:{value:t.receipt.data.payments.cash},on:{input:function(a){a.target.composing||t.$set(t.receipt.data.payments,"cash",a.target.value)}}})]),t._v(" "),e("div",{staticClass:"col-sm-auto mb-3"},[e("label",{staticClass:"form-label",attrs:{for:"paymentElectronic"}},[t._v("Безналичными")]),t._v(" "),e("input",{directives:[{name:"model",rawName:"v-model",value:t.receipt.data.payments.electronic,expression:"receipt.data.payments.electronic"}],staticClass:"form-control form-control-sm",attrs:{type:"number",id:"paymentElectronic",placeholder:"0"},domProps:{value:t.receipt.data.payments.electronic},on:{input:function(a){a.target.composing||t.$set(t.receipt.data.payments,"electronic",a.target.value)}}})]),t._v(" "),e("div",{staticClass:"col-sm-auto mb-3"},[e("label",{staticClass:"form-label",attrs:{for:"paymentPrepaid"}},[t._v("Предоплатой")]),t._v(" "),e("input",{directives:[{name:"model",rawName:"v-model",value:t.receipt.data.payments.prepaid,expression:"receipt.data.payments.prepaid"}],staticClass:"form-control form-control-sm",attrs:{type:"number",id:"paymentPrepaid",placeholder:"0"},domProps:{value:t.receipt.data.payments.prepaid},on:{input:function(a){a.target.composing||t.$set(t.receipt.data.payments,"prepaid",a.target.value)}}})]),t._v(" "),e("div",{staticClass:"col-sm-auto mb-3"},[e("label",{staticClass:"form-label",attrs:{for:"paymentPostpaid"}},[t._v("Постоплатой")]),t._v(" "),e("input",{directives:[{name:"model",rawName:"v-model",value:t.receipt.data.payments.postpaid,expression:"receipt.data.payments.postpaid"}],staticClass:"form-control form-control-sm",attrs:{type:"number",id:"paymentPostpaid",placeholder:"0"},domProps:{value:t.receipt.data.payments.postpaid},on:{input:function(a){a.target.composing||t.$set(t.receipt.data.payments,"postpaid",a.target.value)}}})]),t._v(" "),e("div",{staticClass:"col-sm-auto mb-3"},[e("label",{staticClass:"form-label",attrs:{for:"paymentOther"}},[t._v("Встречным представлением")]),t._v(" "),e("input",{directives:[{name:"model",rawName:"v-model",value:t.receipt.data.payments.other,expression:"receipt.data.payments.other"}],staticClass:"form-control form-control-sm",attrs:{type:"number",id:"paymentOther",placeholder:"0"},domProps:{value:t.receipt.data.payments.other},on:{input:function(a){a.target.composing||t.$set(t.receipt.data.payments,"other",a.target.value)}}})])])])]),t._v(" "),t._m(4),t._v(" "),t._m(5),t._v(" "),t._m(6)]),t._v(" "),e("button",{staticClass:"btn btn-sm btn-primary",attrs:{type:"button"},on:{click:t.saveReceipt}},[t._v("Save")])]):t._e()])}),[function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("h2",{staticClass:"accordion-header",attrs:{id:"clientDataHeading"}},[e("button",{staticClass:"accordion-button",attrs:{type:"button","data-bs-toggle":"collapse","data-bs-target":"#clientDataCollapse","aria-expanded":"true","aria-controls":"clientDataCollapse"}},[t._v("\n                        Данные клиента\n                    ")])])},function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticClass:"accordion-item"},[e("h2",{staticClass:"accordion-header",attrs:{id:"companyDataHeading"}},[e("button",{staticClass:"accordion-button",attrs:{type:"button","data-bs-toggle":"collapse","data-bs-target":"#companyDataCollapse","aria-expanded":"true","aria-controls":"companyDataCollapse"}},[t._v("\n                        Данные организации\n                    ")])]),t._v(" "),e("div",{staticClass:"accordion-collapse collapse show",attrs:{id:"companyDataCollapse","aria-labelledby":"companyDataHeading"}})])},function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("h2",{staticClass:"accordion-header",attrs:{id:"itemDataHeading"}},[e("button",{staticClass:"accordion-button",attrs:{type:"button","data-bs-toggle":"collapse","data-bs-target":"#itemDataCollapse","aria-expanded":"true","aria-controls":"itemDataCollapse"}},[t._v("\n                        Позиции документа\n                    ")])])},function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("h2",{staticClass:"accordion-header",attrs:{id:"paymentDataHeading"}},[e("button",{staticClass:"accordion-button",attrs:{type:"button","data-bs-toggle":"collapse","data-bs-target":"#paymentDataCollapse","aria-expanded":"true","aria-controls":"paymentDataCollapse"}},[t._v("\n                        Данные по оплате\n                    ")])])},function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticClass:"accordion-item"},[e("h2",{staticClass:"accordion-header",attrs:{id:"agentDataHeading"}},[e("button",{staticClass:"accordion-button",attrs:{type:"button","data-bs-toggle":"collapse","data-bs-target":"#agentDataCollapse","aria-expanded":"true","aria-controls":"agentDataCollapse"}},[t._v("\n                        Данные агента\n                    ")])]),t._v(" "),e("div",{staticClass:"accordion-collapse collapse show",attrs:{id:"agentDataCollapse","aria-labelledby":"agentDataHeading"}},[e("div",{staticClass:"accordion-body"},[e("div",{staticClass:"row"},[e("div",{staticClass:"col-sm-auto mb-3"})]),t._v(" "),e("div",{staticClass:"row"})])])])},function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticClass:"accordion-item"},[e("h2",{staticClass:"accordion-header",attrs:{id:"supplierDataHeading"}},[e("button",{staticClass:"accordion-button",attrs:{type:"button","data-bs-toggle":"collapse","data-bs-target":"#supplierDataCollapse","aria-expanded":"true","aria-controls":"supplierDataCollapse"}},[t._v("\n                        Данные поставщика\n                    ")])]),t._v(" "),e("div",{staticClass:"accordion-collapse collapse show",attrs:{id:"supplierDataCollapse","aria-labelledby":"supplierDataHeading"}},[e("div",{staticClass:"accordion-body row"},[t._v("\n                        Supplier Data\n                    ")])])])},function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticClass:"accordion-item"},[e("h2",{staticClass:"accordion-header",attrs:{id:"miscInfoHeading"}},[e("button",{staticClass:"accordion-button",attrs:{type:"button","data-bs-toggle":"collapse","data-bs-target":"#miscInfoCollapse","aria-expanded":"true","aria-controls":"miscInfoCollapse"}},[t._v("\n                        Прочая информация\n                    ")])]),t._v(" "),e("div",{staticClass:"accordion-collapse collapse show",attrs:{id:"miscInfoCollapse","aria-labelledby":"miscInfoHeading"}},[e("div",{staticClass:"accordion-body row"},[t._v("\n                        Misc Info\n                    ")])])])}],!1,null,null,null).exports},855:(t,a,e)=>{e.d(a,{Z:()=>i});const s={data:function(){return{fields:[{key:"id",label:"#"},{key:"created_at",label:"Время"},{key:"connection",label:"Соединение"},{key:"operation",label:"Тип"},{key:"data.total",label:"Сумма"},{key:"state",label:"Статус"}],receipts:{}}},mounted:function(){this.getReceipts()},methods:{getReceipts:function(){var t=this,a=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1;this.$http.get(FiscalRegistrar.basePath+"/api/v1/receipts/?page="+a).then((function(a){t.receipts=a.data}))}}};const i=(0,e(900).Z)(s,(function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",[e("b-table",{attrs:{small:"",striped:"",hover:"",items:t.receipts.data,fields:t.fields},scopedSlots:t._u([{key:"cell(id)",fn:function(a){return[e("a",{attrs:{href:"receipts/"+a.value}},[t._v(t._s(a.value))])]}}])}),t._v(" "),e("pagination",{attrs:{data:t.receipts,limit:2,"show-disabled":!0,align:"center"},on:{"pagination-change-page":t.getReceipts}})],1)}),[],!1,null,null,null).exports}},t=>{var a=a=>t(t.s=a);t.O(0,[143,660],(()=>(a(347),a(67))));t.O()}]);
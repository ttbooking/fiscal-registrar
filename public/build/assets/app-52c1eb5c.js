import{h as y,V as r,B as g,a as f,b as d,c as v,d as w,l as R,L as b}from"./vendor-317c7b7d.js";const x={computed:{FiscalRegistrar(){return FiscalRegistrar},$echo(){return Echo.channel("fiscal-registrar")},agentTypeOptions(){return[{value:null,text:"нет"},...this.buildOptions(this.dictionary.agentTypes)]},taxSystemOptions(){return[{value:null,text:"не выбрано"},...this.buildOptions(this.dictionary.taxSystems)]}},methods:{formatTime(e,t="DD.MM.YYYY HH:mm:ss"){return y(e).format(t)},buildOptions(e,t=null){return t??(t=([a,u])=>({value:a,text:u})),Object.entries(e).map(t)},extractVat(e,t){return parseFloat((e-e/(1+this.dictionary.vatRates[t])).toFixed(2))},emptify(e){if(!e||typeof e!="object")return e;for(const t in e)e[t]=this.emptify(e[t]),e[t]||(e[t]=null);return Object.values(e).every(t=>t===null)&&(e=null),e},enumConnections(){this.$http.get(FiscalRegistrar.basePath+"/api/v1/connection").then(e=>{this.connections=e.data})},resetFilter(){this.query.filter={id:null,external_id:null,internal_id:null,created_from:null,created_to:null,connection:null,operation:null,min_total:null,max_total:null,state:null,email:null,phone:null,fn:null,i:null,fd:null}}},beforeMount(){this.resetFilter(),this.query=merge.all([this.query,JSON.parse(sessionStorage["fiscal-registrar.query"]||"{}"),qs.parse(window.location.search.replace(/^\?/,""))])},watch:{query:{handler:function(e){sessionStorage["fiscal-registrar.query"]=JSON.stringify(e)},deep:!0}},data(){return{query:{hideFilter:!1,hideCreateButton:!1,filter:{},sort:{by:"id",desc:!1}},connections:{},dictionary:{operations:{sell:"приход",sell_refund:"возврат прихода",buy:"расход",buy_refund:"возврат расхода"},states:["не зарегистрирован","зарегистрирован","проведен"],agentTypes:{bank_paying_agent:"банковский платежный агент",bank_paying_subagent:"банковский платежный субагент",paying_agent:"платежный агент",paying_subagent:"платежный субагент",attorney:"поверенный",commission_agent:"комиссионер",another:"агент"},taxSystems:{osn:"общая система налогообложения",usn_income:"упрощенная система налогообложения (доходы)",usn_income_outcome:"упрощенная система налогообложения (доходы минус расходы)",envd:"единый налог на вмененный доход",esn:"единый сельскохозяйственный налог",patent:"патентная система налогообложения"},paymentMethods:{full_prepayment:"предоплата 100%",prepayment:"предоплата",advance:"аванс",full_payment:"полный расчет",partial_payment:"частичный расчет и кредит",credit:"передача в кредит",credit_payment:"оплата кредита"},paymentObjects:{commodity:"товар",excise:"подакцизный товар",job:"работа",service:"услуга",gambling_bet:"ставка азартной игры",gambling_prize:"выигрыш азартной игры",lottery:"лотерейный билет",lottery_prize:"выигрыш лотереи",intellectual_activity:"предоставление результатов интеллектуальной деятельности",payment:"платеж",agent_commission:"агентское вознаграждение",award:"взнос/штраф/вознаграждение/бонус",composite:"составной предмет расчета",another:"иной предмет расчета",property_right:"имущественное право","non-operating_gain":"внереализационный доход",insurance_premium:"страховые взносы",sales_tax:"торговый сбор",resort_fee:"курортный сбор",deposit:"залог",expense:"расход",pension_insurance_ip:"взносы на ОПС ИП",pension_insurance:"взносы на ОПС",medical_insurance_ip:"взносы на ОМС ИП",medical_insurance:"взносы на ОМС",social_insurance:"взносы на ОСС",casino_payment:"платеж казино"},vatTypes:{none:"нет",vat0:"0%",vat10:"10%",vat18:"18%",vat20:"20%",vat110:"10/110",vat118:"18/118",vat120:"20/120"},vatRates:{none:0,vat0:0,vat10:.1,vat18:.18,vat20:.2,vat110:10/110,vat118:18/118,vat120:20/120}}}}},O="modulepreload",E=function(e){return"/vendor/fiscal-registrar/build/"+e},m={},c=function(t,a,u){if(!a||a.length===0)return t();const p=document.getElementsByTagName("link");return Promise.all(a.map(n=>{if(n=E(n),n in m)return;m[n]=!0;const s=n.endsWith(".css"),h=s?'[rel="stylesheet"]':"";if(!!u)for(let o=p.length-1;o>=0;o--){const l=p[o];if(l.href===n&&(!s||l.rel==="stylesheet"))return}else if(document.querySelector(`link[href="${n}"]${h}`))return;const i=document.createElement("link");if(i.rel=s?"stylesheet":O,s||(i.as="script",i.crossOrigin=""),i.href=n,document.head.appendChild(i),s)return new Promise((o,l)=>{i.addEventListener("load",o),i.addEventListener("error",()=>l(new Error(`Unable to preload CSS for ${n}`)))})})).then(()=>t())},F=[{path:"/",redirect:"/receipts"},{path:"/receipts",name:"receipts",component:()=>c(()=>import("./receipts-216b52fd.js"),["assets/receipts-216b52fd.js","assets/_plugin-vue2_normalizer-fe6cccce.js","assets/vendor-317c7b7d.js"])},{path:"/receipt/new",name:"new-receipt",component:()=>c(()=>import("./receipt-4f9c9a4f.js"),["assets/receipt-4f9c9a4f.js","assets/_plugin-vue2_normalizer-fe6cccce.js","assets/vendor-317c7b7d.js","assets/receipt-41f92b93.css"])},{path:"/receipt/from/:id",name:"new-receipt-from-existing",component:()=>c(()=>import("./receipt-4f9c9a4f.js"),["assets/receipt-4f9c9a4f.js","assets/_plugin-vue2_normalizer-fe6cccce.js","assets/vendor-317c7b7d.js","assets/receipt-41f92b93.css"])},{path:"/receipt/:id",name:"receipt",component:()=>c(()=>import("./receipt-4f9c9a4f.js"),["assets/receipt-4f9c9a4f.js","assets/_plugin-vue2_normalizer-fe6cccce.js","assets/vendor-317c7b7d.js","assets/receipt-41f92b93.css"])}];r.use(g);r.use(f);r.use(d);r.prototype.$http=window.axios.create();v.Model.$http=window.axios;window.merge=w;window.qs=R;window.FiscalRegistrar.basePath="/"+window.FiscalRegistrar.path;let _=window.FiscalRegistrar.basePath+"/";(window.FiscalRegistrar.path===""||window.FiscalRegistrar.path==="/")&&(_="/",window.FiscalRegistrar.basePath="");const P=new d({routes:F,mode:"history",base:_,parseQuery(e){return window.qs.parse(e)},stringifyQuery(e){let t=window.qs.stringify(e,{encode:!1});return t?"?"+t:""}});r.component("pagination",b);r.mixin(x);new r({el:"#fiscal-registrar",router:P});

<template>
    <div>
        <div v-if="isLoading" class="loading">
            Loading&#8230;
        </div>
        <form action="subscribe" method="POST">
            <div class="container">
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <input type="hidden" name="stripeToken" v-model="stripeToken">
                            <input type="hidden" name="stripeEmail" v-model="stripeEmail">
                            <label for="subFormControl">Select Subscription</label>
                            <div class="row">
                                <div class="col-md-4 select-style">
                                    <select id="subFormControl" class="form-control" name="product" v-model="plan" >
                                        <option value="" disabled selected>Select your option</option>
                                        <option v-for="plan in plans"
                                                :value="plan.id">
                                            {{plan.name}} &mdash; ${{(plan.price/100)}}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary" type="submit" @click.prevent="buy">Buy</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="alert-danger" v-show="status" v-text="status"></p>
            </div>
        </form>
    </div>
</template>

<script>

    export default {
        props: ['plans'],
        data() {
            return {
                dropdownData: [],
                defaultSelectText: '',
                stripeEmail: '',
                stripeToken: '',
                plan: '',
                status: false,
                isLoading: false
            }

        },
        created() {
            axios.interceptors.request.use((config) => {
                this.setLoading(true);
                return config;
            }, (error) => {
                this.setLoading(false);
            });

            this.status = false;
            this.stripe = StripeCheckout.configure({
                key: appStripe.key,
                image: "https://stripe.com/img/documentation/checkout/marketplace.png",
                locale: "auto",
                panelLabel: 'Subscribe For',
                email: appStripe.user,
                token: (token) => {
                    this.stripeToken = token.id;
                    this.stripeEmail = token.email;
                    axios.post('subscribe', this.$data)
                        .then(response => {
                            alert('Thanks for your payment');
                            location.reload();
                        })
                        .catch(error => this.status = error.response.data.status)
                }
            });
        },
        methods: {
            changeSelect(data, text) {
                console.log(data)
                this.defaultSelectText = text
            },
            buy() {
                let plan = this.findProductById(this.plan);
                this.stripe.open({
                    name: plan.name,
                    description: plan.description,
                    amount: plan.price,
                });
            },
            findProductById(id) {
                return this.plans.find(plan => plan.id == id);
            },
            setLoading(isLoading) {
                if (isLoading) {
                    this.isLoading = true;
                }
            }
        }
    }
</script>
<!--<style>-->
<!--.lds-facebook {-->
<!--display: inline-block;-->
<!--position: relative;-->
<!--width: 64px;-->
<!--height: 64px;-->
<!--}-->

<!--.lds-facebook div {-->
<!--display: inline-block;-->
<!--position: absolute;-->
<!--left: 6px;-->
<!--width: 13px;-->
<!--background: #fed;-->
<!--animation: lds-facebook 1.2s cubic-bezier(0, 0.5, 0.5, 1) infinite;-->
<!--}-->

<!--.lds-facebook div:nth-child(1) {-->
<!--left: 6px;-->
<!--animation-delay: -0.24s;-->
<!--}-->

<!--.lds-facebook div:nth-child(2) {-->
<!--left: 26px;-->
<!--animation-delay: -0.12s;-->
<!--}-->

<!--.lds-facebook div:nth-child(3) {-->
<!--left: 45px;-->
<!--animation-delay: 0;-->
<!--}-->

<!--@keyframes lds-facebook {-->
<!--0% {-->
<!--top: 6px;-->
<!--height: 51px;-->
<!--}-->
<!--50%, 100% {-->
<!--top: 19px;-->
<!--height: 26px;-->
<!--}-->
<!--}-->

<!--</style>-->

<style>
        /* Absolute Center Spinner */
    .loading {
        position: fixed;
        z-index: 999;
        height: 2em;
        width: 2em;
        overflow: show;
        margin: auto;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
    }

    /* Transparent Overlay */
    .loading:before {
        content: '';
        display: block;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.3);
    }

    /* :not(:required) hides these rules from IE9 and below */
    .loading:not(:required) {
        /* hide "loading..." text */
        font: 0/0 a;
        color: transparent;
        text-shadow: none;
        background-color: transparent;
        border: 0;
    }

    .loading:not(:required):after {
        content: '';
        display: block;
        font-size: 10px;
        width: 1em;
        height: 1em;
        margin-top: -0.5em;
        -webkit-animation: spinner 1500ms infinite linear;
        -moz-animation: spinner 1500ms infinite linear;
        -ms-animation: spinner 1500ms infinite linear;
        -o-animation: spinner 1500ms infinite linear;
        animation: spinner 1500ms infinite linear;
        border-radius: 0.5em;
        -webkit-box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.5) -1.5em 0 0 0, rgba(0, 0, 0, 0.5) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
        box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) -1.5em 0 0 0, rgba(0, 0, 0, 0.75) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
    }

    /* Animation */

    @-webkit-keyframes spinner {
        0% {
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -o-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -o-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    @-moz-keyframes spinner {
        0% {
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -o-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -o-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    @-o-keyframes spinner {
        0% {
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -o-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -o-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    @keyframes spinner {
        0% {
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -o-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -o-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

</style>
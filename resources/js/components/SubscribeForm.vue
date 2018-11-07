<template>
    <div>
        <form action="subscribe" method="POST">
            <div class="container">
                <div class="row">
                    <div class="col-sm">
                        <div class="form-group">
                            <input type="hidden" name="stripeToken" v-model="stripeToken">
                            <input type="hidden" name="stripeEmail" v-model="stripeEmail">
                            <select class="form-control" name="product" v-model="plan">
                                <option value="0">Select Plan</option>
                                <option v-for="plan in plans"
                                        :value="plan.id">
                                    {{plan.name}} &mdash; ${{(plan.price/100)}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm">
                        <button class="btn btn-primary" type="submit" @click.prevent="buy">Buy</button>
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
                stripeEmail: '',
                stripeToken: '',
                plan: '0',
                status: false
            }

        },
        created() {
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
                        .then(response => alert('Thanks for your payment'))
                        .catch(error => this.status = error.response.data.status)
                }
            });
        },
        methods: {
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
        }
    }
</script>

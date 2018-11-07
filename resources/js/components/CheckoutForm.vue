<template>
    <div>
        <form action="charge" method="POST">
            <input type="hidden" name="stripeToken" v-model="stripeToken">
            <input type="hidden" name="stripeEmail" v-model="stripeEmail">

            <select name="product" v-model="product">
                <option v-for="product in products"
                        :value="product.id">
                    {{product.name}} &mdash; ${{product.price/100}}
                </option>
            </select>
            <button type="submit" @click.prevent="buy">Buy</button>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['products'],
        data() {
            return {
                stripeEmail: '',
                stripeToken: '',
                product: '1'
            }

        },
        created() {
            this.stripe = StripeCheckout.configure({
                key: appStripe.key,
                image: "https://stripe.com/img/documentation/checkout/marketplace.png",
                locale: "auto",
                panelLabel:'Pay',
                token: (token) => {
                    this.stripeToken = token.id;
                    this.stripeEmail = token.email;
                    axios.post('charge', this.$data).then(response => alert('Thanks for your payment'));
                }
            });
        },
        methods: {
            buy() {
                let product = this.findProductById(this.product);
                console.log(product);
                this.stripe.open({
                    name: product.name,
                    description: product.description,
                    amount: product.price,
                });
            },
            findProductById(id) {
                return this.products.find(product => product.id == id);
            }
        }
    }
</script>

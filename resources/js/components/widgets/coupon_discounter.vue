<script setup>
import {ref, computed} from "vue";
import mixins from "../../mixins";

const props = defineProps({
    unitPrice: {
        type: Number,
        required: true,
    },
    unitAmount: {
        type: Number,
        required: true,
    },
    couponType: {
        type: String,
        required: true,
    },
    currencySymbol: {
        type: String,
        default: '$'
    },
})

const COUPON_STATUS_COLORS = {
    null: '#c0c4bd',
    true: '#58cc02ff',
    false: '#cc3802',
}

const discountValue = ref(0)
const discountFormula = ref('')

const couponStatus = ref('no coupon')
const couponStatusColor = ref(COUPON_STATUS_COLORS[null])

const usualPrice = computed(() => {
    return props.unitPrice * props.unitAmount
})

const discountPrice = computed(() => {
    if (!discountFormula.value || !discountValue.value) {
        return usualPrice.value
    }
    return countTotalDiscountPrice(discountFormula.value, props.unitAmount, props.unitPrice, discountValue.value)
})

const checkCouponInServer = (event) => {
    const serialNumber = event.target.value.trim()
    event.target.classList.remove('important_text')

    if (!serialNumber.length) {
        event.target.value = ''
        couponStatus.value = 'Use coupon for discount!'
        couponStatusColor.value = COUPON_STATUS_COLORS[null]
        return false
    }

    if (serialNumber.length < 5) {
        couponStatus.value = 'Wrong coupon!'
        couponStatusColor.value = COUPON_STATUS_COLORS[false]
        return false
    }

    mixins.methods.getAPI(
        `check_coupon/${props.couponType}/${event.target.value}`,
        null,
        (response) => {
            if (response.status === 'OK') {
                event.target.classList.add('important_text')
                couponStatusColor.value = COUPON_STATUS_COLORS[true]
                discountValue.value = response.data.value
                discountFormula.value = response.data.coupon_unit.formula
                if (response.data.coupon_unit.symbol_placement === "before") {
                    couponStatus.value = `${response.data.coupon_unit.symbol}${response.data.value}`
                } else {
                    couponStatus.value = `${response.data.value}${response.data.coupon_unit.symbol}`
                }
                couponStatus.value = `Valid coupon! (-${couponStatus.value})`
            } else {
                event.target.classList.remove('important_text')
                couponStatus.value = response.data
                couponStatusColor.value = COUPON_STATUS_COLORS[false]
            }
        }
    )
}

function countTotalDiscountPrice(stringFormula, unitsAmount, unitPrice, discount) {
    const priceSign = 'P'
    const discountSign = 'D'
    const amountSign = 'A'
    const formula = stringFormula
        .replace(/A/g, unitsAmount)
        .replace(/P/g, unitPrice)
        .replace(/D/g, discount)
    const result = eval(formula)
    return result < 0 ? 0 : result
}
</script>

<template>
    <div class="template">
        <label>Price: <strong class="important_text">
            <span v-if="discountPrice !== usualPrice" class="old_price">$<span v-text="usualPrice"></span> > </span>
            $<span v-text="discountPrice"></span>
        </strong></label>
        <div class="coupon_field">
            <input type="text" name="coupon" id="coupon" minlength="5" placeholder="COUPON-CODE" @input="checkCouponInServer" required/>
            <span :style="{color: couponStatusColor}" v-text="couponStatus" ></span>
        </div>
    </div>
</template>

<style scoped>
.template {
    max-width: 210px;
}
.old_price {
    color: #90949b;
}
.old_price > span {
    text-decoration: line-through;
}
.coupon_field {
    text-align: center;
}
label{
    font-size: 1.1rem;
}
</style>

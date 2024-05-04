<script setup>
import {ref, computed, onMounted} from "vue";
import mixins from "../../../mixins";
import router from "../../../router";
import SpinCirclePreloader from "../spin_circle_preloader.vue";
import {useEventListener} from "../../../storages/event_storage";

const props = defineProps({
    unitPrice: {
        type: Number,
        default: null,
        required: true,
    },
    unitAmount: {
        type: Number,
        default: null,
        required: true,
    },
    couponTypeId: {
        type: Number,
        default: null,
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

const eventListener = useEventListener()

const coupon = ref(null)
const couponSerialNumber = ref('')
const checkedCouponSerialNumber = ref(null)
const couponStatus = ref('')
const couponStatusColor = ref(COUPON_STATUS_COLORS[null])
const inCheckingProcess = ref(false)
const couponFieldName = ref(null)

const usualPrice = computed(() => {
    if (props.unitPrice !== null && props.unitAmount !== null) {
        return props.unitPrice * props.unitAmount
    }
    return null
})

const discountPrice = computed(() => {
    let price = usualPrice.value
    if (price === 0) {
        setDefaultStatus("No need to use coupon!")
        return price
    }

    couponSerialNumber.value = couponSerialNumber.value.trim()
    if (!couponSerialNumber.value) {
        router.replace({query: null})
        setDefaultStatus()
        return price
    }

    router.replace({query: {coupon: couponSerialNumber.value}})

    if (props.couponTypeId && (!checkedCouponSerialNumber.value || checkedCouponSerialNumber.value !== couponSerialNumber.value)) {
        checkedCouponSerialNumber.value = couponSerialNumber.value
        checkCoupon(props.couponTypeId, couponSerialNumber.value).then((data) => {
            coupon.value = data
        })
    }

    if (coupon.value) {
        couponFieldName.value = 'coupon'
        return countTotalDiscountPrice(
            coupon.value['coupon_unit']['formula'],
            props.unitAmount,
            props.unitPrice,
            coupon.value.value
        )
    }

    return price
})

async function checkCoupon(couponTypeId, serialNumber) {
    inCheckingProcess.value = true

    if (!serialNumber || !serialNumber.length) {
        setDefaultStatus()
        couponFieldName.value = null
        return null
    }

    if (serialNumber.length < 5) {
        couponFieldName.value = null
        setErrorStatus('Wrong coupon code!')
        return null
    }

    const couponData = await getCouponFromServer(couponTypeId, serialNumber)

    if (couponData.status === 'OK') {
        setValueStatus(
            couponData.data.value,
            couponData.data.coupon_unit.symbol,
            couponData.data.coupon_unit.symbol_placement
        )
        return couponData.data
    } else {
        couponFieldName.value = null
        setErrorStatus(couponData.data)
        return null
    }
}

function setValueStatus(value, unitSymbol, unitPlacement) {
    inCheckingProcess.value = false
    couponStatusColor.value = COUPON_STATUS_COLORS[true]
    if (unitPlacement === "before") {
        couponStatus.value = `${unitSymbol}${value}`
    } else {
        couponStatus.value = `${value}${unitSymbol}`
    }
    couponStatus.value = `Valid coupon! [ ${couponStatus.value} ]`
}

function setErrorStatus(errorMessage = 'Can not check coupon!') {
    inCheckingProcess.value = false
    couponStatus.value = errorMessage
    couponStatusColor.value = COUPON_STATUS_COLORS[false]
}

function setDefaultStatus(statusMessage = 'Use coupon for discount!') {
    inCheckingProcess.value = false
    couponStatus.value = statusMessage
    couponStatusColor.value = COUPON_STATUS_COLORS[null]
}

async function getCouponFromServer(couponTypeId, serialNumber) {
    let responseData = null
    await mixins.methods.getAPI(
        `check_coupon/${couponTypeId}/${serialNumber}`,
        null,
        (response) => {
            responseData = response
        }
    )
    return responseData
}

function countTotalDiscountPrice(stringFormula, unitsAmount, unitPrice, discount) {
    const formula = stringFormula
        .replace(/A/g, unitsAmount)
        .replace(/P/g, unitPrice)
        .replace(/D/g, discount)
    const result = eval(formula)
    return result < 0 ? 0 : mixins.methods.numRound(result, 2)
}

onMounted(() => {
    router.isReady().then(() => {
        couponSerialNumber.value = router.currentRoute.value.query['coupon'] ?? ''
    })
})

</script>

<template>
    <div class="template">
        <label>
            <span>Price:</span>
            <SpinCirclePreloader v-show="usualPrice === null" />
            <strong class="important_text" v-show="usualPrice !== null">
                <span v-if="discountPrice !== usualPrice" class="old_price">$<span v-text="usualPrice"></span> > </span>
                $<span v-text="discountPrice"></span>
            </strong>
        </label>
        <div class="coupon_field">
            <input type="text" :name="couponFieldName" id="coupon" minlength="5" placeholder="COUPON-CODE" v-model="couponSerialNumber" />
            <div>
                <SpinCirclePreloader v-show="inCheckingProcess" />
                <span v-show="!inCheckingProcess" :style="{color: couponStatusColor}" v-text="couponStatus" ></span>
            </div>
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
input {
    font-weight: bold;
    text-align: center;
}
label{
    display: flex;
    flex-wrap: wrap;
    font-size: 1.2rem;
    text-align: center;
    align-items: center;
    justify-content: center;
    width: 176px;
}
label > span {
    padding-right: 4px;
    font-size: .8rem;
    text-align: center;
}
.coupon_field > div {
    display: inline-block;
    text-align: center;
}
</style>

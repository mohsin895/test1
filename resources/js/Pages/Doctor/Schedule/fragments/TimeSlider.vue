<template>
    <div
        class="border border-slate-400 overflow-hidden text-3xl font-bold flex flex-col items-center cursor-grab justify-between select-none"
        @wheel="handleWheel"
        :style="`width: ${width};height:${height};`"
        ref="item"
    >
        <div
            class="w-full border h-10 text-base overflow-hidden bg-gradient-to-t to-slate-200 from-transparent text-slate-400 grid place-content-center cursor-pointer py-1"
            ref="upBtn"
        >
            <Transition name="fade">
                <span v-if="modalValue-1>0" :key="modalValue-1">
                    {{ modalValue - 1 }}
                </span>
            </Transition>
        </div>
        <Transition name="fade">
            <span :key="modelValue">
                {{ modalValue }}
            </span>
        </Transition>
        <div
            class="w-full border h-10 text-base overflow-hidden bg-gradient-to-t from-slate-200 to-transparent text-slate-400 grid place-content-center cursor-pointer py-1"
            ref="downBtn"
        >
            <Transition name="fade">
                <span v-if="modalValue+1<=200" :key="modalValue+1">
                    {{ modalValue + 1 }}
                </span>
            </Transition>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from "vue";
import { Icon } from "@/plugins/ui";
import { useMousePressed, useMouse } from "@vueuse/core";

const props = defineProps({
    width: {
        type: String,
        default: "100px",
    },
    height: {
        type: String,
        default: "150px",
    },
});

const modalValue = defineModel<number>();

let interval = null;
const item = ref(null);
const upBtn = ref(null);
const downBtn = ref(null);
let pressedDrag = ref(0)
let mouseClick = ref(false)

const { pressed } = useMousePressed({ target: item, touch: true })
const { pressed: pressedUp } = useMousePressed({ target: upBtn, touch: true })
const { pressed: pressedDown } = useMousePressed({ target: downBtn, touch: true })
const { y } = useMouse({ touch: true })

const handleIncDec = (direction: number) => {
    if (direction > 0 && modalValue.value < 200) modalValue.value++
    if (direction < 0 && modalValue.value > 1) modalValue.value--
};

const handleWheel = (event: WheelEvent) => {
    if (event.deltaY > 0) handleIncDec(1)
    if (event.deltaY < 0) handleIncDec(-1)
};

const addInterval = (direction: number) => {
    mouseClick.value = true
    interval = setInterval(() => handleIncDec(direction), 50);
}

watch([y, pressed], () => {
    if (!(pressed.value && !mouseClick.value)) return
    y.value > pressedDrag.value ? handleIncDec(-1) : handleIncDec(1) // checking mouse is going isDown?
    pressedDrag.value = y.value
})

watch([pressedUp, pressedDown], () => {
    if (pressedUp.value) addInterval(1)
    if (pressedDown.value) addInterval(-1)
    if (!pressedUp.value && !pressedDown.value) {
        mouseClick.value = false
        clearInterval(interval)
    }
})


</script>


<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.1s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
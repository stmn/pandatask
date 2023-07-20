<script setup>

import {Link, usePage} from '@inertiajs/vue3'
import {computed, ref, onMounted} from "vue";
import {Vue3Lottie} from "vue3-lottie";
import {useDark} from "@vueuse/core";

const props = defineProps({
    task: {
        type: Object,
    }
})

const page = usePage()

const auth = computed(() => page.props.auth)

const isRun = computed(() => {
    if (!props.task) {
        return auth.value.time?.task_id;
    }

    return auth.value.user.active_time?.task_id == props.task?.id;
});

const visible = computed(() => auth.value.user.active_time?.task_id || props.task?.id);

const url = computed(() => route(isRun.value ? 'timer.stop' : 'timer.start'))

const isDark = useDark()

const color1 = isDark.value ? [0,0,0,1] : [1,1,1,1];
const color2 = isDark.value ? [1,1,1,1] : [0,0,0,1];
const color3 = isDark.value ? [155/255,155/255,155/255,1] : [100/255,100/255,100/255,1];
const PlayJSON = {"v":"4.8.0","meta":{"g":"LottieFiles AE 3.0.2","a":"","k":"","d":"","tc":""},"fr":25,"ip":0,"op":37,"w":500,"h":500,"nm":"16 Play","ddd":0,"assets":[],"layers":[{"ddd":0,"ind":1,"ty":3,"nm":"Main Stroke width - Color Ctrl","parent":5,"sr":1,"ks":{"o":{"a":0,"k":0,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":0,"k":[0,0,0],"ix":2},"a":{"a":0,"k":[50,50,0],"ix":1},"s":{"a":0,"k":[100,100,100],"ix":6}},"ao":0,"ef":[{"ty":5,"nm":"Stroke width","np":3,"mn":"ADBE Slider Control","ix":1,"en":1,"ef":[{"ty":0,"nm":"Slider","mn":"ADBE Slider Control-0001","ix":1,"v":{"a":0,"k":16,"ix":1}}]}],"ip":0,"op":37,"st":0,"bm":0},{"ddd":0,"ind":2,"ty":4,"nm":"Play","parent":1,"sr":1,"ks":{"o":{"a":0,"k":100,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":1,"k":[{"i":{"x":0.667,"y":1},"o":{"x":0.333,"y":0},"t":3,"s":[62.284,50.794,0],"to":[-0.15,-0.424,0],"ti":[0,0,0]},{"i":{"x":0.667,"y":1},"o":{"x":0.333,"y":0},"t":7,"s":[61.384,48.25,0],"to":[0,0,0],"ti":[-0.15,-0.424,0]},{"t":10,"s":[62.284,50.794,0]}],"ix":2},"a":{"a":0,"k":[0,0,0],"ix":1},"s":{"a":0,"k":[100,100,100],"ix":6}},"ao":0,"shapes":[{"ty":"gr","it":[{"ind":0,"ty":"sh","ix":1,"ks":{"a":1,"k":[{"i":{"x":0.667,"y":1},"o":{"x":0.333,"y":0},"t":3,"s":[{"i":[[0,0],[0,0],[0,0]],"o":[[0,0],[0,0],[0,0]],"v":[[-57.552,71.476],[57.552,0.325],[-57.552,-71.476]],"c":true}]},{"i":{"x":0.667,"y":1},"o":{"x":0.333,"y":0},"t":7,"s":[{"i":[[0,0],[0,0],[0,0]],"o":[[0,0],[0,0],[0,0]],"v":[[-44.32,54.201],[47.552,0.17],[-44.32,-51.25]],"c":true}]},{"t":10,"s":[{"i":[[0,0],[0,0],[0,0]],"o":[[0,0],[0,0],[0,0]],"v":[[-57.552,71.476],[57.552,0.325],[-57.552,-71.476]],"c":true}]}],"ix":2},"nm":"Path 1","mn":"ADBE Vector Shape - Group","hd":false},{"ty":"st","c":{"a":0,"k":color2,"ix":3,"x":"var $bm_rt;\n$bm_rt = thisComp.layer('Main Stroke width - Color Ctrl').effect('highlight')('Color');"},"o":{"a":0,"k":100,"ix":4},"w":{"a":0,"k":16,"ix":5,"x":"var $bm_rt;\n$bm_rt = thisComp.layer('Main Stroke width - Color Ctrl').effect('Stroke width')('Slider');"},"lc":2,"lj":2,"bm":0,"nm":"Stroke 1","mn":"ADBE Vector Graphic - Stroke","hd":false},{"ty":"tr","p":{"a":0,"k":[0,0],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,100],"ix":3},"r":{"a":0,"k":0,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Group 1","np":2,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false}],"ip":0,"op":37,"st":0,"bm":0},{"ddd":0,"ind":3,"ty":4,"nm":"Circle 2","parent":1,"sr":1,"ks":{"o":{"a":0,"k":100,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":0,"k":[50,50,0],"ix":2},"a":{"a":0,"k":[0,0,0],"ix":1},"s":{"a":1,"k":[{"i":{"x":[0.667,0.667,0.667],"y":[1,1,1]},"o":{"x":[0.333,0.333,0.333],"y":[0,0,0]},"t":3,"s":[100,100,100]},{"i":{"x":[0.667,0.667,0.667],"y":[1,1,1]},"o":{"x":[0.333,0.333,0.333],"y":[0,0,0]},"t":7,"s":[97,97,100]},{"t":10,"s":[100,100,100]}],"ix":6}},"ao":0,"shapes":[{"ty":"gr","it":[{"ind":0,"ty":"sh","ix":1,"ks":{"a":0,"k":{"i":[[103.512,0],[0,-103.512],[0,0],[-103.512,0],[0,103.512],[0,0]],"o":[[-103.512,0],[0,0],[0,103.512],[103.512,0],[0,0],[0,-103.512]],"v":[[0,-187.425],[-187.425,0],[-187.425,0],[0,187.425],[187.425,0],[187.425,0]],"c":true},"ix":2},"nm":"Path 1","mn":"ADBE Vector Shape - Group","hd":false},{"ty":"tm","s":{"a":1,"k":[{"i":{"x":[0.667],"y":[1]},"o":{"x":[0.333],"y":[0]},"t":15,"s":[100]},{"t":35,"s":[0]}],"ix":1},"e":{"a":1,"k":[{"i":{"x":[0.667],"y":[1]},"o":{"x":[0.333],"y":[0]},"t":7,"s":[100]},{"t":26,"s":[0]}],"ix":2},"o":{"a":0,"k":0,"ix":3},"m":1,"ix":2,"nm":"Trim Paths 1","mn":"ADBE Vector Filter - Trim","hd":false},{"ty":"st","c":{"a":0,"k":color1,"ix":3,"x":"var $bm_rt;\n$bm_rt = thisComp.layer('Main Stroke width - Color Ctrl').effect('highlight')('Color');"},"o":{"a":0,"k":100,"ix":4},"w":{"a":0,"k":16,"ix":5,"x":"var $bm_rt;\n$bm_rt = thisComp.layer('Main Stroke width - Color Ctrl').effect('Stroke width')('Slider');"},"lc":2,"lj":2,"bm":0,"nm":"Stroke 1","mn":"ADBE Vector Graphic - Stroke","hd":false},{"ty":"tr","p":{"a":0,"k":[0,0],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,100],"ix":3},"r":{"a":0,"k":0,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Group 1","np":3,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false}],"ip":0,"op":37,"st":0,"bm":0},{"ddd":0,"ind":4,"ty":4,"nm":"Circle","parent":1,"sr":1,"ks":{"o":{"a":0,"k":100,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":0,"k":[50,50,0],"ix":2},"a":{"a":0,"k":[0,0,0],"ix":1},"s":{"a":1,"k":[{"i":{"x":[0.667,0.667,0.667],"y":[1,1,1]},"o":{"x":[0.333,0.333,0.333],"y":[0,0,0]},"t":3,"s":[100,100,100]},{"i":{"x":[0.667,0.667,0.667],"y":[1,1,1]},"o":{"x":[0.333,0.333,0.333],"y":[0,0,0]},"t":7,"s":[97,97,100]},{"t":10,"s":[100,100,100]}],"ix":6}},"ao":0,"shapes":[{"ty":"gr","it":[{"ind":0,"ty":"sh","ix":1,"ks":{"a":0,"k":{"i":[[103.512,0],[0,-103.512],[0,0],[-103.512,0],[0,103.512],[0,0]],"o":[[-103.512,0],[0,0],[0,103.512],[103.512,0],[0,0],[0,-103.512]],"v":[[0,-187.425],[-187.425,0],[-187.425,0],[0,187.425],[187.425,0],[187.425,0]],"c":true},"ix":2},"nm":"Path 1","mn":"ADBE Vector Shape - Group","hd":false},{"ty":"st","c":{"a":0,"k":color3,"ix":3,"x":"var $bm_rt;\n$bm_rt = thisComp.layer('Main Stroke width - Color Ctrl').effect('color')('Color');"},"o":{"a":0,"k":100,"ix":4},"w":{"a":0,"k":16,"ix":5,"x":"var $bm_rt;\n$bm_rt = thisComp.layer('Main Stroke width - Color Ctrl').effect('Stroke width')('Slider');"},"lc":2,"lj":2,"bm":0,"nm":"Stroke 1","mn":"ADBE Vector Graphic - Stroke","hd":false},{"ty":"tr","p":{"a":0,"k":[0,0],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,100],"ix":3},"r":{"a":0,"k":0,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Group 1","np":2,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false}],"ip":0,"op":37,"st":0,"bm":0},{"ddd":0,"ind":5,"ty":3,"nm":"Zero | troke width - Color Ctrl","sr":1,"ks":{"o":{"a":0,"k":0,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":0,"k":[250,250,0],"ix":2},"a":{"a":0,"k":[0,0,0],"ix":1},"s":{"a":0,"k":[100,100,100],"ix":6}},"ao":0,"ip":0,"op":37,"st":0,"bm":0}],"markers":[]};
const StopJSON = {"v":"4.8.0","meta":{"g":"LottieFiles AE 3.0.2","a":"","k":"","d":"","tc":""},"fr":25,"ip":0,"op":37,"w":500,"h":500,"nm":"17 Pause","ddd":0,"assets":[],"layers":[{"ddd":0,"ind":1,"ty":3,"nm":"Main Stroke width - Color Ctrl","parent":5,"sr":1,"ks":{"o":{"a":0,"k":0,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":0,"k":[0,0,0],"ix":2},"a":{"a":0,"k":[50,50,0],"ix":1},"s":{"a":0,"k":[100,100,100],"ix":6}},"ao":0,"ef":[{"ty":5,"nm":"Stroke width","np":3,"mn":"ADBE Slider Control","ix":1,"en":1,"ef":[{"ty":0,"nm":"Slider","mn":"ADBE Slider Control-0001","ix":1,"v":{"a":0,"k":16,"ix":1}}]}],"ip":0,"op":37,"st":0,"bm":0},{"ddd":0,"ind":2,"ty":4,"nm":"Pause","parent":1,"sr":1,"ks":{"o":{"a":0,"k":100,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":0,"k":[50,50,0],"ix":2},"a":{"a":0,"k":[0,0,0],"ix":1},"s":{"a":0,"k":[100,100,100],"ix":6}},"ao":0,"shapes":[{"ty":"gr","it":[{"ind":0,"ty":"sh","ix":1,"ks":{"a":1,"k":[{"i":{"x":0.667,"y":1},"o":{"x":0.333,"y":0},"t":3,"s":[{"i":[[0,0],[0,0]],"o":[[0,0],[0,0]],"v":[[39.709,-88.153],[39.709,86.565]],"c":false}]},{"i":{"x":0.667,"y":1},"o":{"x":0.333,"y":0},"t":7,"s":[{"i":[[0,0],[0,0]],"o":[[0,0],[0,0]],"v":[[39.587,-61.653],[39.669,72.565]],"c":false}]},{"t":10,"s":[{"i":[[0,0],[0,0]],"o":[[0,0],[0,0]],"v":[[39.709,-88.153],[39.709,86.565]],"c":false}]}],"ix":2},"nm":"Path 1","mn":"ADBE Vector Shape - Group","hd":false},{"ind":1,"ty":"sh","ix":2,"ks":{"a":1,"k":[{"i":{"x":0.667,"y":1},"o":{"x":0.333,"y":0},"t":3,"s":[{"i":[[0,0],[0,0]],"o":[[0,0],[0,0]],"v":[[-39.709,-88.153],[-39.709,86.565]],"c":false}]},{"i":{"x":0.667,"y":1},"o":{"x":0.333,"y":0},"t":7,"s":[{"i":[[0,0],[0,0]],"o":[[0,0],[0,0]],"v":[[-39.831,-61.653],[-39.748,72.565]],"c":false}]},{"t":10,"s":[{"i":[[0,0],[0,0]],"o":[[0,0],[0,0]],"v":[[-39.709,-88.153],[-39.709,86.565]],"c":false}]}],"ix":2},"nm":"Path 2","mn":"ADBE Vector Shape - Group","hd":false},{"ty":"st","c":{"a":0,"k":color2,"ix":3,"x":"var $bm_rt;\n$bm_rt = thisComp.layer('Main Stroke width - Color Ctrl').effect('highlight')('Color');"},"o":{"a":0,"k":100,"ix":4},"w":{"a":0,"k":16,"ix":5,"x":"var $bm_rt;\n$bm_rt = thisComp.layer('Main Stroke width - Color Ctrl').effect('Stroke width')('Slider');"},"lc":2,"lj":2,"bm":0,"nm":"Stroke 1","mn":"ADBE Vector Graphic - Stroke","hd":false},{"ty":"tr","p":{"a":0,"k":[0,0],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,100],"ix":3},"r":{"a":0,"k":0,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Group 1","np":3,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false}],"ip":0,"op":37,"st":0,"bm":0},{"ddd":0,"ind":3,"ty":4,"nm":"Circle 3","parent":1,"sr":1,"ks":{"o":{"a":0,"k":100,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":0,"k":[50,50,0],"ix":2},"a":{"a":0,"k":[0,0,0],"ix":1},"s":{"a":1,"k":[{"i":{"x":[0.667,0.667,0.667],"y":[1,1,1]},"o":{"x":[0.333,0.333,0.333],"y":[0,0,0]},"t":3,"s":[100,100,100]},{"i":{"x":[0.667,0.667,0.667],"y":[1,1,1]},"o":{"x":[0.333,0.333,0.333],"y":[0,0,0]},"t":7,"s":[97,97,100]},{"t":10,"s":[100,100,100]}],"ix":6}},"ao":0,"shapes":[{"ty":"gr","it":[{"ind":0,"ty":"sh","ix":1,"ks":{"a":0,"k":{"i":[[103.512,0],[0,-103.512],[0,0],[-103.512,0],[0,103.512],[0,0]],"o":[[-103.512,0],[0,0],[0,103.512],[103.512,0],[0,0],[0,-103.512]],"v":[[0,-187.425],[-187.425,0],[-187.425,0],[0,187.425],[187.425,0],[187.425,0]],"c":true},"ix":2},"nm":"Path 1","mn":"ADBE Vector Shape - Group","hd":false},{"ty":"tm","s":{"a":1,"k":[{"i":{"x":[0.667],"y":[1]},"o":{"x":[0.333],"y":[0]},"t":15,"s":[100]},{"t":35,"s":[0]}],"ix":1},"e":{"a":1,"k":[{"i":{"x":[0.667],"y":[1]},"o":{"x":[0.333],"y":[0]},"t":7,"s":[100]},{"t":26,"s":[0]}],"ix":2},"o":{"a":0,"k":0,"ix":3},"m":1,"ix":2,"nm":"Trim Paths 1","mn":"ADBE Vector Filter - Trim","hd":false},{"ty":"st","c":{"a":0,"k":color1,"ix":3,"x":"var $bm_rt;\n$bm_rt = thisComp.layer('Main Stroke width - Color Ctrl').effect('highlight')('Color');"},"o":{"a":0,"k":100,"ix":4},"w":{"a":0,"k":16,"ix":5,"x":"var $bm_rt;\n$bm_rt = thisComp.layer('Main Stroke width - Color Ctrl').effect('Stroke width')('Slider');"},"lc":2,"lj":2,"bm":0,"nm":"Stroke 1","mn":"ADBE Vector Graphic - Stroke","hd":false},{"ty":"tr","p":{"a":0,"k":[0,0],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,100],"ix":3},"r":{"a":0,"k":0,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Group 1","np":3,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false}],"ip":0,"op":37,"st":0,"bm":0},{"ddd":0,"ind":4,"ty":4,"nm":"Circle 2","parent":1,"sr":1,"ks":{"o":{"a":0,"k":100,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":0,"k":[50,50,0],"ix":2},"a":{"a":0,"k":[0,0,0],"ix":1},"s":{"a":1,"k":[{"i":{"x":[0.667,0.667,0.667],"y":[1,1,1]},"o":{"x":[0.333,0.333,0.333],"y":[0,0,0]},"t":3,"s":[100,100,100]},{"i":{"x":[0.667,0.667,0.667],"y":[1,1,1]},"o":{"x":[0.333,0.333,0.333],"y":[0,0,0]},"t":7,"s":[97,97,100]},{"t":10,"s":[100,100,100]}],"ix":6}},"ao":0,"shapes":[{"ty":"gr","it":[{"ind":0,"ty":"sh","ix":1,"ks":{"a":0,"k":{"i":[[103.512,0],[0,-103.512],[0,0],[-103.512,0],[0,103.512],[0,0]],"o":[[-103.512,0],[0,0],[0,103.512],[103.512,0],[0,0],[0,-103.512]],"v":[[0,-187.425],[-187.425,0],[-187.425,0],[0,187.425],[187.425,0],[187.425,0]],"c":true},"ix":2},"nm":"Path 1","mn":"ADBE Vector Shape - Group","hd":false},{"ty":"st","c":{"a":0,"k":color3,"ix":3,"x":"var $bm_rt;\n$bm_rt = thisComp.layer('Main Stroke width - Color Ctrl').effect('color')('Color');"},"o":{"a":0,"k":100,"ix":4},"w":{"a":0,"k":16,"ix":5,"x":"var $bm_rt;\n$bm_rt = thisComp.layer('Main Stroke width - Color Ctrl').effect('Stroke width')('Slider');"},"lc":2,"lj":2,"bm":0,"nm":"Stroke 1","mn":"ADBE Vector Graphic - Stroke","hd":false},{"ty":"tr","p":{"a":0,"k":[0,0],"ix":2},"a":{"a":0,"k":[0,0],"ix":1},"s":{"a":0,"k":[100,100],"ix":3},"r":{"a":0,"k":0,"ix":6},"o":{"a":0,"k":100,"ix":7},"sk":{"a":0,"k":0,"ix":4},"sa":{"a":0,"k":0,"ix":5},"nm":"Transform"}],"nm":"Group 1","np":2,"cix":2,"bm":0,"ix":1,"mn":"ADBE Vector Group","hd":false}],"ip":0,"op":37,"st":0,"bm":0},{"ddd":0,"ind":5,"ty":3,"nm":"Zero | troke width - Color Ctrl","sr":1,"ks":{"o":{"a":0,"k":0,"ix":11},"r":{"a":0,"k":0,"ix":10},"p":{"a":0,"k":[250,250,0],"ix":2},"a":{"a":0,"k":[0,0,0],"ix":1},"s":{"a":0,"k":[100,100,100],"ix":6}},"ao":0,"ip":0,"op":37,"st":0,"bm":0}],"markers":[]};

const rendererSettings = {

}

// const playBtn = ref();

// onMounted(() => {
//     // playBtn.value.goToAndStop(2,2);
//     playBtn.value.play();
// });
</script>

<template>
    <Link v-if="visible"
          :href="url"
          method="post"
          as="span"
          style="display: inline-flex;"
          :data="{ task: task?.id }"
          :only="['auth', 'flash', 'times']"
          class="timer"
          preserve-scroll>
        <template v-if="isRun">
            <slot name="stop">
<!--                <font-awesome-icon style="font-size: 26px; cursor: pointer; color: #cc2222;" icon="circle-stop"  />-->
                <i class="fa-solid fa-circle-stop hover-opacity" style="font-size: 26px; cursor: pointer; color: var(--el-color-danger-light-3);"></i>


<!--                <Vue3Lottie style="cursor: pointer;"-->
<!--                            :animationData="StopJSON"-->
<!--                            :auto-play="true"-->
<!--                            :loop="false"-->
<!--                            :height="36"-->
<!--                            :width="36" />-->
            </slot>
        </template>
        <template v-else>
            <slot name="play">
<!--                    <VideoPlay/>-->
<!--                <font-awesome-icon style="font-size: 26px; cursor: pointer;" icon="circle-play"  />-->
                <i class="fa-solid fa-circle-play hover-opacity" style="font-size: 26px; cursor: pointer; color: var(--el-color-info);"></i>

<!--                <Vue3Lottie style="cursor: pointer;"-->
<!--                            :animationData="PlayJSON"-->
<!--                            :auto-play="true"-->
<!--                            :loop="false"-->
<!--                            :height="36"-->
<!--                            :width="36" />-->
            </slot>
        </template>
    </Link>
</template>

<style lang="scss" scoped>
.el-icon {
    opacity: 0.8;

    &:hover {
        //opacity: 1;
        color: var(--el-text-color-primary)
    }
}
</style>

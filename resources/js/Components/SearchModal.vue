<script setup>
import { nextTick, ref, watch } from 'vue';
import { dayjs } from '../translation.config.js';
import throttle from 'lodash/throttle.js';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const props = defineProps({
    active: Boolean
})

const results = ref({
    type: Object,
    default: {
        pupils: [],
        learningMaterials: [],
        lessons: []
    }

})

let search = ref('')
let input = ref(null)

watch(search, throttle(value => {
    loadResults(value)
}, 100))

const loadResults = (input) => {
    axios.get(route('search', { search: input }))
        .then(response => {
            results.value = response.data.result;
        })
        .catch(error => {
            console.log(error)
        })
}
defineExpose({
    focus: () => {
        loadResults('')
        nextTick().then(() => {
            input.value.focus()
        })
    }
});
</script>

<template>
    <div
        class="max-h-full relative w-full flex flex-col bg-white k3u76 jpq01 shadow-[0_10px_40px_10px_rgba(0,0,0,0.08)] dark:bg-neutral-800 dark:shadow-[0_10px_40px_10px_rgba(0,0,0,0.2)]">
        <!-- Input -->
        <div class="relative">
            <div class="absolute inset-y-0 atruh flex items-center i24sd ootlu ps-3.5">
                <svg class="flex-shrink-0 sfo8l qcpnt qkm2y dark:text-white/60" fill="none"
                     height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     viewBox="0 0 24 24"
                     width="24" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
            </div>
            <input ref="input"
                   v-model="search"
                   class="p-3 ps-10 block w-full bg-white border-transparent border-b-gray-200 text-sm focus:outline-none focus:ring-0 focus:border-transparent focus:border-b-gray-200 kko9e dark:bg-neutral-800 dark:border-transparent dark:border-b-neutral-700 dark:text-neutral-400 dark:placeholder:text-neutral-400"
                   placeholder="Напишите запрос для поиска" type="text">
        </div>
        <!-- End Input -->

        <!-- Body -->
        <div
            class="h-[500px] p-4 overflow-y-auto overflow-hidden bx801 mws46 szov1 bp4e4 m2y1i dark:[&amp;::-webkit-scrollbar-track]:bg-neutral-700 dark:[&amp;::-webkit-scrollbar-thumb]:bg-neutral-500">
            <!--            <div class="pb-4 njeai bg8gm d1k81 dark:border-neutral-700">
                                              <span class="block rvi38 text-gray-400 tn07p dark:text-neutral-500">
                                                Topics
                                              </span>

                             Tag Group
                            <div class="flex flex-wrap nx6y7 sm:gap-2">
                                <a class="inline-flex items-center pla0f rvi38 eass7 v7056 l66z3 d1k81 zvbcs zdxcz kdz1q iwtls focus:outline-none focus:bg-gray-100 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" href="#">
                                    <svg class="flex-shrink-0 xtsb0 h-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg>
                                    Inbox
                                </a>
                                <a class="inline-flex items-center pla0f rvi38 eass7 v7056 l66z3 d1k81 zvbcs zdxcz kdz1q iwtls focus:outline-none focus:bg-gray-100 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" href="#">
                                    <svg class="flex-shrink-0 xtsb0 h-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                                    Activity
                                </a>
                                <a class="inline-flex items-center pla0f rvi38 eass7 v7056 l66z3 d1k81 zvbcs zdxcz kdz1q iwtls focus:outline-none focus:bg-gray-100 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" href="#">
                                    <svg class="flex-shrink-0 xtsb0 h-3.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M14.9636 7.95706C14.9636 7.03203 13.8052 6.1554 12.0292 5.61175C12.439 3.80157 12.2569 2.36137 11.4542 1.90027C11.2692 1.79212 11.0529 1.74089 10.8167 1.74089V2.3756C10.9476 2.3756 11.0529 2.40121 11.1411 2.4496C11.5282 2.67161 11.6961 3.51695 11.5652 4.60419C11.5339 4.87174 11.4827 5.15351 11.4201 5.44099C10.8622 5.30438 10.2531 5.19907 9.61271 5.13076C9.22848 4.60419 8.82999 4.12602 8.42868 3.70764C9.35654 2.84523 10.2275 2.37275 10.8195 2.37275V1.73804C10.0368 1.73804 9.01216 2.2959 7.97612 3.26362C6.9401 2.30159 5.91546 1.74942 5.13274 1.74942V2.38413C5.72192 2.38413 6.59569 2.85376 7.52358 3.71049C7.12509 4.12887 6.72663 4.60419 6.34807 5.13076C5.70484 5.19907 5.09574 5.30438 4.53786 5.44384C4.47241 5.15921 4.42403 4.88314 4.38988 4.61842C4.25609 3.53117 4.42118 2.68584 4.80541 2.46098C4.89079 2.40975 5.0018 2.38698 5.13274 2.38698V1.75227C4.89364 1.75227 4.67732 1.8035 4.48948 1.91166C3.68969 2.37275 3.51038 3.81009 3.92308 5.6146C2.15272 6.16108 1 7.03488 1 7.95706C1 8.88209 2.15842 9.75872 3.93446 10.3024C3.52461 12.1126 3.70677 13.5528 4.50941 14.0138C4.69443 14.122 4.91072 14.1732 5.14982 14.1732C5.93254 14.1732 6.95718 13.6154 7.99319 12.6477C9.02924 13.6097 10.0539 14.1619 10.8366 14.1619C11.0757 14.1619 11.292 14.1106 11.4799 14.0025C12.2796 13.5414 12.4589 12.104 12.0463 10.2995C13.8109 9.7559 14.9636 8.87924 14.9636 7.95706ZM11.2578 6.05862C11.1525 6.42577 11.0216 6.80434 10.8736 7.1829C10.7569 6.9552 10.6345 6.72748 10.5007 6.49978C10.3698 6.27209 10.2303 6.0501 10.0909 5.83378C10.495 5.89356 10.885 5.96753 11.2578 6.05862ZM9.95427 9.08986C9.73225 9.4741 9.50455 9.83843 9.2683 10.1771C8.84422 10.2141 8.41446 10.2341 7.98182 10.2341C7.55203 10.2341 7.12227 10.2141 6.701 10.18C6.46479 9.84128 6.23424 9.4798 6.01222 9.09841C5.7959 8.72556 5.59951 8.347 5.42022 7.96561C5.59668 7.5842 5.7959 7.20282 6.00937 6.82996C6.23139 6.4457 6.45908 6.0814 6.69533 5.74269C7.11942 5.70569 7.54918 5.68576 7.98182 5.68576C8.41161 5.68576 8.84137 5.70569 9.26263 5.73984C9.49885 6.07855 9.7294 6.44003 9.95142 6.82141C10.1677 7.19427 10.3641 7.57283 10.5434 7.95421C10.3641 8.33562 10.1677 8.717 9.95427 9.08986ZM10.8736 8.71986C11.0273 9.10127 11.1582 9.48265 11.2664 9.85266C10.8935 9.94374 10.5007 10.0206 10.0937 10.0803C10.2332 9.86121 10.3727 9.63633 10.5036 9.40579C10.6345 9.17809 10.7569 8.94755 10.8736 8.71986ZM7.98752 11.7568C7.72282 11.4835 7.45812 11.179 7.19625 10.846C7.45242 10.8574 7.71427 10.8659 7.97897 10.8659C8.24652 10.8659 8.51121 10.8602 8.77024 10.846C8.51406 11.179 8.24937 11.4835 7.98752 11.7568ZM5.8699 10.0803C5.46575 10.0206 5.07581 9.94659 4.70295 9.85551C4.80826 9.48835 4.9392 9.10979 5.08719 8.73123C5.2039 8.95895 5.32628 9.18665 5.46004 9.41434C5.59383 9.64204 5.73044 9.86403 5.8699 10.0803ZM7.97329 4.15735C8.23799 4.43058 8.50269 4.73513 8.76454 5.06813C8.50836 5.05676 8.24652 5.0482 7.98182 5.0482C7.71427 5.0482 7.44957 5.05391 7.19057 5.06813C7.44672 4.73513 7.71142 4.43058 7.97329 4.15735ZM5.86705 5.83378C5.72759 6.05295 5.58813 6.27779 5.45719 6.50834C5.32628 6.73603 5.2039 6.96372 5.08719 7.19142C4.9335 6.81004 4.80256 6.42863 4.69443 6.05862C5.06726 5.97039 5.46004 5.89356 5.86705 5.83378ZM3.29122 9.39727C2.28365 8.96748 1.63186 8.40393 1.63186 7.95706C1.63186 7.5102 2.28365 6.9438 3.29122 6.51686C3.536 6.41155 3.80354 6.31764 4.07962 6.22941C4.24186 6.78726 4.45533 7.36788 4.72003 7.96276C4.45818 8.55476 4.24756 9.13257 4.08817 9.68756C3.80639 9.59933 3.53884 9.50257 3.29122 9.39727ZM4.82249 13.4645C4.4354 13.2425 4.26749 12.3972 4.3984 11.3099C4.4297 11.0424 4.48096 10.7606 4.54356 10.4731C5.10144 10.6097 5.71052 10.7151 6.35092 10.7834C6.73516 11.3099 7.13364 11.7881 7.53495 12.2065C6.6071 13.0689 5.73615 13.5414 5.14412 13.5414C5.01603 13.5385 4.90787 13.5129 4.82249 13.4645ZM11.5738 11.2957C11.7075 12.383 11.5425 13.2283 11.1582 13.4531C11.0728 13.5044 10.9618 13.5272 10.8309 13.5272C10.2417 13.5272 9.36794 13.0575 8.44006 12.2008C8.83854 11.7824 9.237 11.3071 9.61556 10.7805C10.2588 10.7122 10.8679 10.6069 11.4258 10.4674C11.4912 10.7549 11.5425 11.031 11.5738 11.2957ZM12.6696 9.39727C12.4248 9.50257 12.1572 9.59651 11.8812 9.68474C11.7189 9.12687 11.5055 8.54624 11.2408 7.95136C11.5026 7.35936 11.7132 6.78156 11.8726 6.22656C12.1544 6.31479 12.4219 6.41155 12.6724 6.51686C13.68 6.94665 14.3318 7.5102 14.3318 7.95706C14.3289 8.40393 13.6771 8.97033 12.6696 9.39727Z"></path>
                                        <path d="M7.97885 9.25778C8.69722 9.25778 9.27959 8.67543 9.27959 7.95706C9.27959 7.23869 8.69722 6.65632 7.97885 6.65632C7.26048 6.65632 6.67814 7.23869 6.67814 7.95706C6.67814 8.67543 7.26048 9.25778 7.97885 9.25778Z"></path>
                                    </svg>
                                    React
                                </a>
                                <a class="inline-flex items-center pla0f rvi38 eass7 v7056 l66z3 d1k81 zvbcs zdxcz kdz1q iwtls focus:outline-none focus:bg-gray-100 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" href="#">
                                    <svg class="flex-shrink-0 xtsb0 h-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" x2="8" y1="13" y2="13"></line><line x1="16" x2="8" y1="17" y2="17"></line><line x1="10" x2="8" y1="9" y2="9"></line></svg>
                                    Files
                                </a>
                            </div>
                             End Tag Group
                        </div>-->
            <div v-if="results.pupils && results.pupils.length > 0"
                 class="pb-4 njeai bg8gm d1k81 dark:border-neutral-700">
                <span class="block rvi38 text-gray-400 tn07p dark:text-neutral-500">
                    Ученики
                </span>
                <!-- List Group -->
                <ul class="-mx-2.5">
                    <!-- List Item -->
                    <li v-for="pupil in results.pupils" :key="pupil.id"
                        class="hover:bg-indigo-600 focus:bg-indigo-600 hover:text-white focus:text-white">
                        <a :href="route('pupil.show', pupil.id)"
                           class="py-2 px-3 flex items-center gap-x-3 zvbcs nq4w8">
<!--                            <img :src="pupil.user.profile_photo_path" alt="Image Description"-->
<!--                                 class="flex-shrink-0 w-5 h-5 rounded-full">-->
                            <span class="text-sm v7056 dark:text-neutral-200">
                                          {{ pupil.full_name }}
                                        </span>
                            <!--                            <span class="text-xs text-teal-600">
                                                            Active now
                                                        </span>-->
                        </a>
                    </li>
                    <!-- End List Item -->
                </ul>
                <!-- End List Group -->
            </div>


            <div v-if="results.lessons && results.lessons.length > 0"
                 class="pb-4 njeai bg8gm d1k81 dark:border-neutral-700">
                                  <span class="block rvi38 text-gray-400 tn07p dark:text-neutral-500">
                                    Уроки
                                  </span>
                <!-- List Group -->
                <ul class="-mx-2.5">
                    <!-- List Item -->
                    <li v-for="lesson in results.lessons" :key="lesson.id"
                        class="hover:bg-indigo-600 focus:bg-indigo-600 hover:text-white focus:text-white">
                        <a :href="route('lesson.show', lesson.id)"
                           class="py-2 px-3 flex items-center gap-x-2 zvbcs nq4w8">
            <span
                class="flex items-center bg-white w-8 h-8 border border-gray-200 rounded-lg justify-center dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300">
            <font-awesome-icon icon="fa-regular fa-file-lines"/>
            </span>
                            <div class="text-sm v7056 dark:text-neutral-200">
                                <span v-if="lesson.title">{{ lesson.title }}</span>
                                <span v-else class="text-gray-400 font-normal italic">Без названия</span>
                            </div>

                            <span v-if="lesson.starts_at" class="text-xs qkm2y dark:text-neutral-500">
                                          {{ dayjs(lesson.starts_at).format('DD.MM.YYYY HH:mm') }}
                                        </span>
                        </a>
                    </li>
                    <!-- End List Item -->

                </ul>
                <!-- End List Group -->
            </div>

            <div v-if="results.learningMaterials && results.learningMaterials.length > 0"
                 class="pb-4 njeai bg8gm d1k81 dark:border-neutral-700">
                                  <span class="block rvi38 text-gray-400 tn07p dark:text-neutral-500">
                                    Учебные материалы
                                  </span>

                <!-- List Group -->
                <ul class="-mx-2.5">
                    <!-- List Item -->
                    <li v-for="learningMaterial in results.learningMaterials" :key="learningMaterial.id"
                        class="hover:bg-indigo-600 focus:bg-indigo-600 hover:text-white focus:text-white">
                        <a :href="route('learning_material.show', learningMaterial.id)"
                           class="py-2 px-3 flex items-center gap-x-3 zvbcs nq4w8">
            <span
                class="flex items-center bg-white w-8 h-8 border border-gray-200 rounded-lg justify-center dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300">
            <font-awesome-icon icon="fa-regular fa-lightbulb"/>
            </span>
                            <span class="text-sm dark:text-neutral-200 line-clamp-1">
                                          {{ learningMaterial.title }}
                                        </span>
                    <ul class="text-sm text-gray-600">
                        <li v-for="tag in learningMaterial.tags"
                            :key="tag.id" class="inline-block relative pe-8 last:pe-0 last-of-type:before:hidden before:absolute before:top-1/2 before:end-3 before:-translate-y-1/2 before:size-1 before:bg-gray-300 before:rounded-full dark:text-neutral-400 dark:before:bg-neutral-600">
                            {{ tag.name.ru }}
                        </li>
                    </ul>

                        </a>
                    </li>
                    <!-- End List Item -->
                </ul>
                <!-- End List Group -->
            </div>

        </div>
        <!-- End Body -->

        <!-- Empty State -->
        <div id="hs-pro-dfkmec" class="hidden flex lgqw8 pdrgo items-center">
            <svg class="flex-shrink-0 y3buf h75us n8wpz text-gray-400 dark:text-neutral-500" fill="currentColor"
                 height="16"
                 viewBox="0 0 16 16" width="16" xmlns="http://www.w3.org/2000/svg">
                <path clip-rule="evenodd"
                      d="M12 14C13.1046 14 14 13.1046 14 12C14 10.8955 13.1046 10 12 10C10.8954 10 10 10.8955 10 12C10 13.1046 10.8954 14 12 14ZM12 15C13.6569 15 15 13.6569 15 12C15 10.3432 13.6569 9.00003 12 9.00003C10.3431 9.00003 9 10.3432 9 12C9 13.6569 10.3431 15 12 15Z"
                      fill-rule="evenodd"></path>
                <path clip-rule="evenodd"
                      d="M7.5 0H2C1.46957 0 0.960859 0.210714 0.585786 0.585786C0.210714 0.960859 0 1.46957 0 2V14C0 14.5304 0.210714 15.0391 0.585786 15.4142C0.960859 15.7893 1.46957 16 2 16H10C10.286 16 10.5656 15.9388 10.8214 15.8235C10.2726 15.6546 9.77417 15.3707 9.35418 15H2C1.73478 15 1.48043 14.8946 1.29289 14.7071C1.10536 14.5196 1 14.2652 1 14V2C1 1.73478 1.10536 1.48043 1.29289 1.29289C1.48043 1.10536 1.73478 1 2 1H7.5V3C7.5 3.39782 7.65804 3.77936 7.93934 4.06066C8.22064 4.34196 8.60218 4.5 9 4.5H11V8.12602C11.3196 8.04375 11.6547 8 12 8V4.5L7.5 0Z"
                      fill-rule="evenodd"></path>
                <path clip-rule="evenodd"
                      d="M3.64645 6.64648C3.84171 6.45122 4.15829 6.45122 4.35355 6.64648L6 8.29292L7.64645 6.64648C7.84171 6.45122 8.15829 6.45122 8.35355 6.64648C8.54882 6.84174 8.54882 7.15832 8.35355 7.35358L6.70711 9.00003L8.35355 10.6465C8.54882 10.8417 8.54882 11.1583 8.35355 11.3536C8.15829 11.5488 7.84171 11.5488 7.64645 11.3536L6 9.70714L4.35355 11.3536C4.15829 11.5488 3.84171 11.5488 3.64645 11.3536C3.45118 11.1583 3.45118 10.8417 3.64645 10.6465L5.29289 9.00003L3.64645 7.35358C3.45118 7.15832 3.45118 6.84174 3.64645 6.64648Z"
                      fill-rule="evenodd"></path>
                <path clip-rule="evenodd"
                      d="M13.6464 13.6465C13.8417 13.4512 14.1583 13.4512 14.3536 13.6465L15.8536 15.1465C16.0488 15.3417 16.0488 15.6583 15.8536 15.8536C15.6583 16.0488 15.3417 16.0488 15.1464 15.8536L13.6464 14.3536C13.4512 14.1583 13.4512 13.8417 13.6464 13.6465Z"
                      fill-rule="evenodd"></path>
            </svg>
            <p class="text-sm text-gray-400 dark:text-neutral-500">
                Нет данных
            </p>
        </div>
        <!-- End Empty State -->

        <!-- Footer -->
        <div class="p-4 flex azaj8 items-center lz37y d1k81 dark:border-neutral-700">
            <div class="inline-flex items-center gap-x-2">
                <div
                    class="w-5 v834c flex lgqw8 pdrgo items-center bg-white l66z3 d1k81 rvi38 ouifk qkm2y tgfrq sg5ei dark:bg-neutral-800 dark:border-neutral-700">
                    <svg class="flex-shrink-0 sfo8l qcpnt qkm2y dark:text-neutral-500" fill="none" height="7"
                         viewBox="0 0 16 7" width="16" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M14.5 4.99712C14.3882 5.37064 13.7772 6 12.938 6C12.0987 6 11.2022 5.04455 11.2022 3.78386V3.19193C11.2022 1.8838 12.0314 1.00102 12.938 1.00102C13.8447 1.00102 14.3662 1.49912 14.5 2.11899M9.65213 1.93635C9.51016 1.44272 8.86395 0.975267 8.11903 1.00102C7.37431 1.02666 6.70773 1.59524 6.70773 2.26932C6.70773 2.9434 7.14855 3.18797 8.11955 3.29149C9.09034 3.39501 9.61853 3.95222 9.65213 4.54259C9.68573 5.13296 9.1754 6 8.11955 6C7.19423 6 6.50191 5.06384 6.46306 4.52706M4.91442 5.08667C4.63772 5.70602 4.06396 5.9999 3.29279 5.9999C2.52162 5.9999 1.5 5.34854 1.5 3.70223V3.22237C1.5 2.13651 2.2571 1.00081 3.29279 1.00081C4.32858 1.00081 5.01753 2.07562 4.91442 3.34559H1.8104"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.2"></path>
                    </svg>
                </div>
                <span class="text-xs qkm2y dark:text-neutral-500">
                    - закрыть
                </span>
            </div>
        </div>
        <!-- End Footer -->
    </div>
</template>


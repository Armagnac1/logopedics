<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    canResetPassword: Boolean,
    status: String
});

const page = usePage();

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const isLoading = ref(false);

const submit = () => {
    const maxRetries = 3;
    let attempts = 0;

    const attemptLogin = () => {
        isLoading.value = true;
        form.transform(data => ({
            ...data,
            remember: form.remember ? 'on' : ''
        })).post(route('login'), {
            onFinish: () => {
                isLoading.value = false;
                if (page.props.flash.csrf_valid === false && attempts < maxRetries) {
                    attempts++;
                    attemptLogin();
                }
                form.reset('password');
            }
        });
    };

    attemptLogin();
};
</script>

<template>
    <Head :title="$t('passwordForm.login')"/>

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo/>
        </template>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel :value="$t('common.email')" for="email"/>
                <TextInput
                    id="email"
                    v-model="form.email"
                    autocomplete="username"
                    autofocus
                    class="mt-1 block w-full"
                    required
                    type="email"
                />
                <InputError :message="form.errors.email" class="mt-2"/>
            </div>

            <div class="mt-4">
                <InputLabel :value="$t('passwordForm.password')" for="password"/>
                <TextInput
                    id="password"
                    v-model="form.password"
                    autocomplete="current-password"
                    class="mt-1 block w-full"
                    required
                    type="password"
                />
                <InputError :message="form.errors.password" class="mt-2"/>
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox v-model:checked="form.remember" name="remember"/>
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{
                            $t('passwordForm.remember_me')
                        }}</span>
                </label>
            </div>
            <div class="flex items-center justify-end mt-4">
                <Link v-if="canResetPassword" :href="route('password.request')"
                      class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    {{ $t('passwordForm.forgot_password') }}
                </Link>

                <PrimaryButton :class="{ 'opacity-25': form.processing || isLoading }"
                               :disabled="form.processing || isLoading" class="ms-4">
                    <span v-if="isLoading">{{ $t('passwordForm.loading') }}</span>
                    <span v-else>{{ $t('passwordForm.login') }}</span>
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>

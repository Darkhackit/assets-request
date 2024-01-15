<script setup lang="ts">
import { useGenerateImageVariant } from '@/@core/composable/useGenerateImageVariant'

import authV1LoginMaskDark from '@images/pages/auth-v1-login-mask-dark.png'
import authV1LoginMaskLight from '@images/pages/auth-v1-login-mask-light.png'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import axiosIns from "@axios";
import {useAppAbility} from "@/plugins/casl/useAppAbility";
import {useRouter,useRoute} from "vue-router";

const form = ref({
  email: '',
  password: '',
  remember: false,
})

const authV1ThemeLoginMask = useGenerateImageVariant(authV1LoginMaskLight, authV1LoginMaskDark)
const isPasswordVisible = ref(false)
const processing = ref(false)
const ability = useAppAbility()
const router = useRouter()
const route = useRoute()

const errors = ref([])

const clearErrors = (field: any) => {
  delete errors.value[field]
}
const login = async () => {
  processing.value = true
  try {
    let response = await axiosIns.post('/api/auth/login',form.value)
    const {accessToken , token_type,expires_in,user} = (await response.data)
    console.log(user.ability)
    ability.update(user.ability)
    window.localStorage.setItem("userAbilities",JSON.stringify(user.ability))
    window.localStorage.setItem("accessToken",JSON.stringify(accessToken))
    window.localStorage.setItem("userData",JSON.stringify(user.user))
    window.localStorage.setItem("userRole",JSON.stringify(user.roles))
    processing.value = false

    router.push(route.query.to ? String(route.query.to) : '/')
    console.log(response)
  }catch (e) {
    console.log(e)
    if (e.response.status === 422) {
      errors.value = e.response.data.errors
    }
    processing.value = false
  }
}
</script>

<template>
  <div class="auth-wrapper d-flex align-center justify-center pa-4">
    <VCard
      class="auth-card pa-2 pt-7"
      max-width="448"
    >
      <VCardItem class="justify-center">
        <template #prepend>
          <div class="me-n2">
            <VNodeRenderer :nodes="themeConfig.app.logo" />
          </div>
        </template>

        <VCardTitle class="text-2xl font-weight-bold text-capitalize">

        </VCardTitle>
      </VCardItem>

      <VCardText class="pt-2 text-center">
        <h5 class="text-h5 mb-1">
          <span class="text-capitalize">ASSET FINANCIAL REQUEST</span>
        </h5>

        <p class="mb-0 text-center">
          Please sign-in to your account
        </p>
      </VCardText>

      <VCardText>
        <VForm @submit.prevent="login">
          <VRow>
            <!-- email -->
            <VCol cols="12">
              <VTextField
                v-model="form.email"
                @input="clearErrors('email')"
                :class="{'v-field--error': errors.email}"
                autofocus
                label="Email"
                type="email"
                placeholder="johndoe@primeinsuranceghana.com"
              />
              <small style="color: #ff4c20" v-if="errors.email">{{errors.email[0]}}</small>
            </VCol>

            <!-- password -->
            <VCol cols="12">
              <VTextField
                v-model="form.password"
                label="Password"
                @input="clearErrors('password')"
                :class="{'v-field--error': errors.password}"
                placeholder="············"
                :type="isPasswordVisible ? 'text' : 'password'"
                :append-inner-icon="isPasswordVisible ? 'mdi-eye-outline' : 'mdi-eye-off-outline'"
                @click:append-inner="isPasswordVisible = !isPasswordVisible"
              />
              <small style="color: #ff4c20" v-if="errors.password">{{errors.password[0]}}</small>
              <!-- remember me checkbox -->
              <div class="d-flex align-center justify-space-between flex-wrap mt-1 mb-4">
                <VCheckbox
                  v-model="form.remember"
                  label="Remember me"
                />


              </div>

              <!-- login button -->
              <VBtn
                block
                type="submit"
                :loading="processing"
              >
                Login
              </VBtn>
            </VCol>

            <!-- create account -->
            <VCol
              cols="12"
              class="text-center text-base"
            >
              <span>New on our platform?</span>

            </VCol>

            <VCol
              cols="12"
              class="d-flex align-center"
            >
              <VDivider />
              <span class="mx-4"></span>
              <VDivider />
            </VCol>

            <!-- auth providers -->
            <VCol
              cols="12"
              class="text-center"
            >
<!--              <AuthProvider />-->
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
    <VImg
      :src="authV1ThemeLoginMask"
      class="d-none d-md-block auth-footer-mask"
    />
  </div>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";
</style>

<route lang="yaml">
 meta:
   layout: blank
   action: read
   subject: Auth
   redirectIfLoggedIn: true
</route>

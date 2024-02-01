<script setup lang="ts">
import avatar1 from '@images/avatars/avatar-1.png'
import axiosIns from "@axios";
import {useRouter} from "vue-router";
import {ref} from "vue";
import axios from "@axios";

const user = JSON.parse(window.localStorage.getItem('userData'))
const router = useRouter()
const addModal = ref(false)
const errors = ref([])
const processing = ref(false)

const form = ref({
  current_password:"",
  password:"",
  password_confirmation:"",
})
const logout = async () => {
  try {
    await axiosIns.post("/api/user/logout")
    window.localStorage.removeItem("userAbilities")
    window.localStorage.removeItem("accessToken")
    window.localStorage.removeItem("userData")
     window.location.href = "/login"
  }catch (e) {
    console.log(e)
  }
}
const clearErrors = (field) => {
  delete errors.value[field]
}

const addData = async () => {
  try {
    processing.value = true
    await axios.post(`/api/user/employee/changePassword`,form.value)
    addModal.value = false
    processing.value = false
  }catch (e) {
    errors.value = e.response.data.errors
    processing.value = false
  }
}


</script>

<template>
  <VBadge
    dot
    location="bottom right"
    offset-x="3"
    offset-y="3"
    bordered
    color="success"
  >
    <VAvatar
      class="cursor-pointer"
      color="primary"
      variant="tonal"
    >
      <VImg :src="avatar1" />

      <!-- SECTION Menu -->
      <VMenu
        activator="parent"
        width="230"
        location="bottom end"
        offset="14px"
      >
        <VList>
          <!-- 👉 User Avatar & Name -->
          <VListItem>
            <template #prepend>
              <VListItemAction start>
                <VBadge
                  dot
                  location="bottom right"
                  offset-x="3"
                  offset-y="3"
                  color="success"
                >
                  <VAvatar
                    color="primary"
                    variant="tonal"
                  >
                    <VImg :src="avatar1" />
                  </VAvatar>
                </VBadge>
              </VListItemAction>
            </template>

            <VListItemTitle class="font-weight-semibold">
              {{ user.name }}
            </VListItemTitle>
            <VListItemSubtitle>Admin</VListItemSubtitle>
          </VListItem>

          <VDivider class="my-2" />

          <!-- 👉 Settings -->
          <VListItem link @click.prevent="addModal = true">
            <template #prepend>
              <VIcon
                class="me-2"
                icon="tabler-settings"
                size="22"
              />
            </template>

            <VListItemTitle>
              Change Password
            </VListItemTitle>
          </VListItem>



          <!-- Divider -->
          <VDivider class="my-2" />

          <!-- 👉 Logout -->
          <VListItem @click.prevent="logout">
            <template #prepend>
              <VIcon
                class="me-2"
                icon="tabler-logout"
                size="22"
              />
            </template>

            <VListItemTitle>Logout</VListItemTitle>
          </VListItem>
        </VList>
      </VMenu>
      <!-- !SECTION -->
    </VAvatar>
  </VBadge>

  <!--   add data-->
  <VDialog
    v-model="addModal"
    persistent
    max-width="600"
  >
    <!-- Dialog Content -->
    <VCard title="Change Password">
      <DialogCloseBtn
        variant="text"
        size="small"
        @click="addModal = false"
      />

      <VCardText>
        <VRow>
          <VCol
            cols="12"
          >
            <VTextField
              type="password"
              v-model="form.current_password"
              :readonly="processing"
              label="Curent Password"
              @input="clearErrors('current_password')"
              :class="{'v-field--error': errors?.current_password}"
            />
            <small style="color: #ff4c20" v-if="errors.current_password">{{errors.current_password[0]}}</small>
          </VCol>
          <VCol
            cols="12"
          >
            <VTextField
              type="password"
              v-model="form.password"
              :readonly="processing"
              label="Password"
              @input="clearErrors('password')"
              :class="{'v-field--error': errors?.password}"
            />
            <small style="color: #ff4c20" v-if="errors.password">{{errors.password[0]}}</small>
          </VCol>
          <VCol
            cols="12"
          >
            <VTextField
              type="password"
              v-model="form.password_confirmation"
              :readonly="processing"
              label="Password Confirmattion"
              @input="clearErrors('password_confirmation')"
              :class="{'v-field--error': errors?.password_confirmation}"
            />
            <small style="color: #ff4c20" v-if="errors.password_confirmation">{{errors.password_confirmation[0]}}</small>
          </VCol>
        </VRow>
      </VCardText>

      <VCardActions>
        <VSpacer />
        <VBtn
          color="error"
          @click="addModal = false"
        >
          Close
        </VBtn>
        <VBtn
          @click.prevent="addData"
          :disabled="processing"
          :loading="processing"
          color="success"
        >
          Save
        </VBtn>
      </VCardActions>
    </VCard>
  </VDialog>
  <!--   end data-->
</template>

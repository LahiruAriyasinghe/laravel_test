<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">
      <span class="text-indigo-400 font-medium">Customer/</span> Create
    </h1>
    <div class="bg-white rounded-md shadow overflow-hidden max-w-3xl">
      <form @submit.prevent="store">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="form.name" :error="form.errors.name" class="pr-6 pb-8 w-full lg:w-full" label="Name" />
          <text-input v-model="form.nic" :error="form.errors.nic" class="pr-6 pb-8 w-full lg:w-full" label="NIC" />
          <text-input v-model="form.address" :error="form.errors.address" class="pr-6 pb-8 w-full lg:w-full" label="Address" />
           <div v-for="(contact, k) in form.contacts" :key="k">
          <text-input v-model="contact.telephone" :error="form.errors[ `contacts.${k}.telephone`]" class="pr-6 pb-8 w-full lg:w-full" label="Phone"/>   
          <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="deleteContact(k)">Remove</button>
          <button class="text-blue-600 hover:underline" type="button" @click="addContact(k)" v-show="k == form.contacts.length-1">Add Contact</button>
          </div>

        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Create Organization</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'

export default {
  metaInfo: { title: 'Create Customer' },
  components: {
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: null,
        nic: null,
        address: null,
        contacts: [
            {
              telephone: "",
            },
        ],
      }),
    }
  },
  methods: {
    store() {
      this.form.post(this.route('organizations.store'))
    },
    addContact(index) {
        this.form.contacts.push({
            telephone: "",
        });
    },
    deleteContact(index) {
          this.form.contacts.splice(index, 1);
      },
  },
}
</script>

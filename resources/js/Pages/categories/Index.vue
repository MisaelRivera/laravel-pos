<script setup>
    import { ref, reactive } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    const props = defineProps({
        categoriesProp: {
            type: Object,
        }
    });
    const categories = ref(props.categoriesProp);
    const category = reactive({
        name: null,
    });
    const openCreateModal = () => {};
    const isOpenCreate = ref(false);
    const hasMessage = ref('');
    const handleOpenCreate = () => {
        isOpenCreate.value = true;
    };
    const handleCreate = async() => {
        console.log(category.name);
        try {
            const res = await axios({
                method: 'post',
                url: '/categories',
                data: {
                    name: category.name
                }
            });
            categories.value = res.data;
            isOpenCreate.value = false;
            hasMessage.value = 'Se ha creado una nueva categoria correctamente'
        } catch (e) {
            if (Object.keys(e.response.data).includes('errors')) {
                hasMessage.value = e.response.data.message;
                
            }
            console.log(e.response.data);
        }
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="pl-5">
            <input 
                type="text"
                class="w-64 h-12 rounded-full border-slate-300"
                placeholder="Buscar...">
        </div>
        <div class="flex justify-between ml-5">
            <h1>Lista de categorias</h1>
            <button 
                class="bg-indigo-700 text-white text-center px-4 py-2 rounded-s-full"
                @click="handleOpenCreate">
                Add new
            </button>
        </div>
        <hr>
        <a-alert
            type="success"
            v-if="hasMessage"
            :message="hasMessage"
            closable/>
        <table class="border w-full ml-5">
            <thead>
                <tr class="bg-indigo-700 text-white py-3">
                    <th class="text-left w-10">Sr#</th>
                    <th class="text-left">Category</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="category in categories">
                    <td class="py-3">{{ category.id }}</td>
                    <td class="py-3">{{ category.name }}</td>
                    <td class="py-3"></td>
                    <td class="py-3"></td>
                </tr>
            </tbody>
        </table>
        <a-modal
            title="Crear una categoria"
            @ok="handleCreate"
            v-model:open="isOpenCreate">
            <div class="mb-4">
                <label for="name">Categoria</label>
                <input 
                    type="text"
                    name="name"
                    id="name"
                    v-model="category.name"
                    class="rounded py-2 px-4 h-8 w-full">
            </div>
            <a-alert
            type="error"
            v-if="hasError"
            :message="hasError"
            closable/>
        </a-modal>
    </AuthenticatedLayout>
</template>
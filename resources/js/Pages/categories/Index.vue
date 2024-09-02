<script setup>
    import { ref } from 'vue';
    import { useMessages } from '@/Composables/messages';
    import { useForm, Link } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { DeleteOutlined, EditOutlined } from '@ant-design/icons-vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    const props = defineProps({
        categories: {
            type: Object,
        },
        errors: Object
    });
    const { getMessage } = useMessages();
    const createForm = useForm({
        name: null,
    });

    const editForm = useForm({
        id: null,
        name: null,
    });

    const deleteForm = useForm({
        id: null,
        name: null
    });
    const isOpenCreate = ref(false);
    const isOpenEdit = ref(false);
    const hasMessage = ref('');
    const handleOpenCreate = () => {
        isOpenCreate.value = true;
    };
    const handleCreate = () => {
        createForm.post('/categories', {
            onSuccess: () => {
                isOpenCreate.value = false;
            },
            onError: () => {
                hasMessage.value = '';
            }
        });
        
    };
    const handleCreateClose = () => {
        isOpenCreate.value = false;
        createForm.name = '';
    };

    const handleOpenEdit = (category) => {
        editForm.id = category.id;
        editForm.name = category.name;
        isOpenEdit.value = true;
    };

    const handleEditClose = () => {
        isOpenEdit.value = false;
        editForm.name = '';
    };

    const handleEdit = () => {
        try {
            editForm.put(route('categories.update', editForm.id));
            isOpenEdit.value = false;
        } catch (e) {
            console.log(e);
        }
    };

    const handleDelete = (category) => {
        deleteForm.name = category.id;
        deleteForm.name = category.name;
        console.log(category);
        deleteForm.delete(route('categories.destroy', category));
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="pl-5 flex justify-between">
            <input 
                type="text"
                class="w-64 h-12 rounded-full border-slate-300"
                placeholder="Buscar...">
            <Pagination
                :links="categories.links" />
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
            v-if="getMessage()"
            :message="getMessage()"/>
            <div class="max-h-[500px] overflow-scroll">
                <table class="border ml-5 w-full">
                    <thead>
                        <tr>
                            <th class="text-left w-10 sticky top-0 bg-indigo-700 text-white py-2">Sr#</th>
                            <th class="text-left sticky top-0 bg-indigo-700 text-white py-2">Category</th>
                            <th class="sticky top-0 bg-indigo-700 text-white py-2 w-12"></th>
                            <th class="sticky top-0 bg-indigo-700 text-white py-2 w-12"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="category in categories.data">
                            <td class="py-3 border">{{ category.id }}</td>
                            <td class="py-3 border">{{ category.name }}</td>
                            <td class="py-3 text-left border">
                                <EditOutlined 
                                   style="color:white; background-color: blue; border-radius: 50%; font-size: 20px; padding: 0.375rem"
                                   @click="() => handleOpenEdit(category)" />
                            </td>
                            <td class="py-3 text-left border">
                                <form
                                    @submit.prevent="() => handleDelete(category)">
                                    <button>
                                        <DeleteOutlined
                                            style="color:white; background-color: firebrick; border-radius: 50%; font-size: 20px; padding: 0.375rem" />
                                    </button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <a-modal
            title="Crear una categoria"
            v-model:open="isOpenCreate"
            :ok-button-props="{ class: ['hidden'] }"
            @cancel="handleCreateClose">
            <a-form
                :model="createForm"
                layout="vertical"
                @finish="handleCreate">
                <div class="mb-4">
                    <a-form-item
                        label="Categoria"
                        name="name"
                        :rules="[{ required: false, message: 'Ingrese la categoria' }]">
                        <a-input
                            v-model:value="createForm.name"/>
                    </a-form-item>
                    <p v-if="errors.name" class="text-red-700"> {{ errors.name }}</p>
                </div>
                <button class="rounded py-2 px-4 bg-green-500 text-white mt-2">Crear</button>
            </a-form>
        </a-modal>
        <a-modal
            title="Editar la categoria"
            v-model:open="isOpenEdit"
            :ok-button-props="{ class: ['hidden'] }"
            @cancel="handleEditClose">
            <a-form
                :model="editForm"
                layout="vertical"
                @finish="handleEdit">
                <div class="mb-4">
                    <a-form-item
                        label="Categoria"
                        name="name"
                        :rules="[{ required: false, message: 'Ingrese la categoria' }]">
                        <a-input
                            v-model:value="editForm.name"/>
                    </a-form-item>
                    <p v-if="errors.name" class="text-red-700"> {{ errors.name }}</p>
                </div>
                <button class="rounded py-2 px-4 bg-blue-500 text-white mt-2">Editar</button>
            </a-form>
        </a-modal>
    </AuthenticatedLayout>
</template>
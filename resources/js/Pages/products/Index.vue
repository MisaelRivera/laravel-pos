<script setup>
    import { ref } from 'vue';
    import { useMessages } from '@/Composables/messages';
    import { useForm, Link, usePage } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { DeleteOutlined, EditOutlined } from '@ant-design/icons-vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    const props = defineProps({
        categories: {
            type: Array,
        },

        products: {
            type: Object
        },

        errors: Object,
    });

    const assetImg = usePage().props.asset.img;
    console.log(assetImg);
    const { getMessage } = useMessages();
    const createForm = useForm({
        name: null,
        purchase_price: null,
        sale_price: null,
        category_id: null,
        img_uri: null,
    });

    const editForm = useForm({
        id: null,
        name: null,
        purchase_price: null,
        sale_price: null,
        category_id: null,
        img_uri: null,
    });

    const deleteForm = useForm({
        id: null,
        name: null,
    });

    const createImgUrl = ref(null);

    const isOpenCreate = ref(false);
    const isOpenEdit = ref(false);
    const hasMessage = ref('');
    const handleOpenCreate = () => {
        isOpenCreate.value = true;
    };
    const handleCreate = () => {
        createForm.post('/products', {
            onSuccess: () => {
                isOpenCreate.value = false;
            },
            onError: () => {
                hasMessage.value = '';
            },
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

    const swapImg = (ev) => {
        if (ev.target.files && ev.target.files[0]) {
        // Create a new FileReader object
        const reader = new FileReader();
        
        // Set up a function to run when the file is read
        reader.onload = function(e) {
            // Update the src attribute of the img element with the data URL of the selected image
            createImgUrl.value = e.target.result;
            console.log(e.target.result);
        }
        
        // Read the selected file as a data URL
        reader.readAsDataURL(ev.target.files[0]);
    }
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
                        <tr v-for="product in products.data">
                            <td class="py-3 border">{{ product.id }}</td>
                            <td class="py-3 border">{{ product.name }}</td>
                            <td class="py-3 text-left border">
                                <EditOutlined 
                                   style="color:white; background-color: blue; border-radius: 50%; font-size: 20px; padding: 0.375rem"
                                   @click="() => handleOpenEdit(product)" />
                            </td>
                            <td class="py-3 text-left border">
                                <form
                                    @submit.prevent="() => handleDelete(product)">
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
                @finish="handleCreate"
                enctype="multipart/form-data">
                <a-row :gutter="6">
                    <a-col :span="16">
                        <a-row :gutter="6">
                            <a-col :span="24">
                                <div class="mb-4">
                                    <a-form-item
                                        label="Nombre"
                                        name="name"
                                        :rules="[{ required: false, message: 'Ingrese el producto' }]">
                                        <a-input
                                            v-model:value="createForm.name"
                                            class="h-8 rounded border-slate-300"/>
                                    </a-form-item>
                                    <p v-if="errors.name" class="text-red-700"> {{ errors.name }}</p>
                                </div>
                            </a-col>
                        </a-row>
                        <a-row :gutter="6">
                            <a-col :span="12">
                                <div class="mb-4">
                                    <a-form-item
                                        label="Precio de compra"
                                        name="purchase_price"
                                        :rules="[{ required: false, message: 'Ingrese el precio de compra' }]">
                                        <a-input-number
                                            v-model:value="createForm.purchase_price"
                                            class="w-full"/>
                                    </a-form-item>
                                    <p v-if="errors.purchase_price" class="text-red-700"> {{ errors.purchase_price }}</p>
                                </div>
                            </a-col>
                            <a-col :span="12">
                                <div class="mb-4">
                                    <a-form-item
                                        label="Precio de venta"
                                        name="sale_price"
                                        :rules="[{ required: false, message: 'Ingrese el precio de venta' }]">
                                        <a-input-number
                                            v-model:value="createForm.sale_price"
                                            class="w-full"/>
                                    </a-form-item>
                                    <p v-if="errors.sale_price" class="text-red-700"> {{ errors.sale_price }}</p>
                                </div>
                            </a-col>
                        </a-row>
                        <a-row :gutter="6">
                            <a-col :span="12">
                                <div class="mb-4">
                                    <a-form-item
                                        label="Categoria"
                                        name="category_id"
                                        :rules="[{ required: false, message: 'Ingrese el producto' }]">
                                        <a-select
                                            v-model:value="createForm.category_id">
                                            <a-select-option
                                                :value="null">
                                                Elija una opcion
                                            </a-select-option>
                                            <a-select-option
                                                v-for="category in categories"
                                                :value="category.id">
                                                {{ category.name }}
                                            </a-select-option>
                                        </a-select>
                                    </a-form-item>
                                    <p v-if="errors.category_id" class="text-red-700"> {{ errors.category_id }}</p>
                                </div>
                            </a-col>
                        </a-row>
                    </a-col>
                    <a-col :span="8">
                        <h2>Imagen del producto</h2>
                        <img 
                            :src="`${createImgUrl ? createImgUrl:assetImg + '/no_image.jpg'}`" 
                            alt="">
                        <a-form-item>
                            <a-input
                                type="file"
                                class="text-xs"
                                name="img_uri"
                                v-model:value="createForm.img_uri"
                                @change="swapImg"/>
                            <p v-if="errors.img_uri" class="text-red-700"> {{ errors.img_uri }}</p>
                        </a-form-item>
                    </a-col>
                </a-row>
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
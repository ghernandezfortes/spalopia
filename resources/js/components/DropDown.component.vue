<template>
    <div class="dropdown">
        <select @change="sendItem" v-model="serviceSelected" class="form-control">
            <option  :value="{id:item.id, price:item.price}" v-for="(item, index) of services" :key="index">{{item.name}} - ({{item.price}} €)</option>            
        </select>
    </div>
</template>

<script>
    // componente para renderizar un dropdown con los servicios disponibles.
    export default {
        name: 'dropdown',
        props: ['services'],
        data: function() {
            return {
                serviceSelected: null,
            }
        },
        methods: {
            //   emitimos el evento de item seleccionado
            sendItem() {
                this.$emit('sendItem', this.serviceSelected);
            }
        }
    }
</script>

<style>

</style>



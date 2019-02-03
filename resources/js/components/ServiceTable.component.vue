<template>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Price</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(service, index) of services" :key="index">
                <th>{{service.id}}</th>
                <th>{{service.name}}</th>
                <td>{{service.description}}</td>
                <td>{{service.price}}</td>
            </tr>
        </tbody>
    </table>
</template>


<script>
    // componente para renderisar una tabla con los servicios
    export default {
        name: 'service-table',
        props: ['services']
    }
</script>

<style scoped>

.table .thead-dark th { 
    color: #fff;
    background-color: #343a40;
    border-color: #363a40;
}
tr:hover {
    cursor: pointer;
    background-color: #e6e5e578;
}

</style>



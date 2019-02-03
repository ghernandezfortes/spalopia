<template>
    <div>
        <div>
            <nav-bar v-on:activeItem="activeItem" :navOptions="navOptions" :itemActive="itemActive"></nav-bar>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12 body mt-10">
                    <h3>{{itemActive}}</h3>
                </div>
                <div class="col-12 mt-20" >
                    <service-table :services="services" v-if="itemActive == 'Services'"></service-table>
                    <schedules v-if="itemActive == 'Shedules'"></schedules>
                    <reservations :services="services" v-if="itemActive == 'Reservations'"></reservations>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// imports
import navBar from './NavBar.component.vue'
import serviceTable from './ServiceTable.component.vue'
import schedules from './Schedules.component.vue'
import reservations from './Reservations.component.vue'

    //componente principal de la aplicación Vue
    export default {
        data: function() {
            return {
                navOptions: {
                    title: 'Spalopia Test',
                    items: [
                        {name: 'Services'},
                        {name: 'Shedules'},
                        {name: 'Reservations'},
                    ]
                },
                itemActive: 'Services',
                services: [],
                urlGetServices: 'http://localhost:8000/api/services'
            }
        },
        methods: {
            // controlamos que item del navbar esta activo
            activeItem(item) {
                this.itemActive = item;
                switch (item) {
                    case 'Services':
                        this.getServices();
                        break;
                    default:
                        break;
                }
            },
            // obtenemos los servicios desde el servidor
            getServices() {
                axios
                .get(this.urlGetServices)
                .then(response => (this.services = response.data))
                .catch(response => {
                    console.log(response);
                })
            }
        },
        mounted () {
            this.getServices();
        },
        components: {
            'nav-bar': navBar,
            'service-table': serviceTable,
            'schedules': schedules,
            reservations
        }
    }
</script>

<style>

.mt-10 {
    margin-top: 10px;
}

.mt-20 {
    margin-top: 20px;
}
h3 {
    text-decoration: underline;
}



</style>


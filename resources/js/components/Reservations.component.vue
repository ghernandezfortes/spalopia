<template>
    <div class="card">
        <div class="card-body">
            <div class="row">
                 <div class="col-12 messages">
                    <h4 class="text-success" v-if="success">{{successMessage}}.</h4>
                    <h4 class="text-danger" v-if="error.status == true">{{error.message}}</h4>
                </div>
                <div class="row" v-if="!sent">
                    <div class="col-12 col-lg-4">
                        <label>Date End:</label>
                        <datepicker v-model="reservation.date"></datepicker>
                    </div>
                    <div class="col-6 col-lg-4">
                        <label class="col-12">Hour Start:</label>
                        <vue-timepicker v-model="hour_start" format="HH:mm" :minute-interval="60" ></vue-timepicker>
                    </div>
                    <div class="col-6 col-lg-4">
                        <label class="col-12">Hour End:</label>
                        <vue-timepicker v-model="hour_end" format="HH:mm" :minute-interval="60" ></vue-timepicker>                
                    </div>
                    <div class="col-12">
                        <label class="col-12">Service</label>
                        <dropdown 
                            v-on:sendItem="getService"
                            :services="services">
                        </dropdown>
                    </div>
                    <div class="col-12">
                        <label class="col-12">Customer Name:</label>
                        <input type="text" class="form-control" v-model="reservation.customer_name" />
                    </div>
                    <div class="col-12">
                        <label class="col-12">Comments:</label>
                        <textarea  class="form-control" cols="30" rows="10"
                            v-model="reservation.comments">
                        </textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 text-center" v-if="!sent">
            <button class="btn btn-info" @click="sendReservation">Submit</button>
        </div>
    </div>
</template>


<script>
    // imports
    import VueTimepicker from 'vue2-timepicker' // componente vue para renderizar un timepicker
    import Datepicker from 'vuejs-datepicker' // componente vue para renderizar un datepicker
    import dropDown from './DropDown.component.vue'
    import moment from 'moment'

    // componente para gestionar el alta de una reserva
    export default {
        name: 'reservations',
        props: ['services'], // heredamos los servicios desde el MainComponent
        data: function() {
            return {
                urlsendReservation:'http://localhost:8000/api/sendReservation',
                hour_start: {
                    HH: "08",
                    mm: "00",
                },
                hour_end: {
                    HH: "09",
                    mm: "00",
                },
                reservation: {
                    date: null,
                    total_price: null,
                    service_id: null,
                    customer_name: null,
                    comments: null,
                    schedule_id: null,
                    hour_start: null,
                    hour_end: null
                },
                error: {
                    status: false,
                    message: ''
                },               
                success: false,
                successMessage: '',
                sent: false
            }
        },
        methods: {
            // enviamos la reserva
            async sendReservation() {
                this.formatHours();
                this.reservation.date = this.formatDate(this.reservation.date);

                if (this.checkDataForm()) {
                    this.error.status = true;
                    return null;
                }

                axios({
                    method: 'get',
                    url: this.urlsendReservation,
                    params: this.reservation,
                    headers: {
                        'content-type': `multipart/form-data;`,
                    },
                })
                .then(response => {
                    // comprobamos que el envio ha sido correcto o no y lo gestionamos
                    this.sent = true;
                    if (typeof response.data === 'object') { 
                        if (response.data.error == true) {
                            this.setError(true, response.data.message);
                        } else {
                            this.success = true;
                            this.successMessage = response.data.message;
                        }
                    }
                })
                .catch(response => {
                    this.setError(true, response);
                })
            },
            // formateamos los dias
            formatDate(date) {
                return moment(date).format('YYYY-MM-DD') 
            },
            // metodo del evento que recibimos desde el componente de dropdown
            getService(service) {
                this.reservation.service_id = service.id;
                this.reservation.total_price = service.price;
            },
            // formateamos las horas
            formatHours()  {
                this.reservation.hour_start = `${this.hour_start.HH}:${this.hour_start.mm}`
                this.reservation.hour_end = `${this.hour_end.HH}:${this.hour_end.mm}`
            },
            // establecemos los errores
            setError(status, message){
                this.error.status = status
                this.error.message = message
            },
            checkDataForm() {

                let result = false;
                 this.error.message = '';

                if (this.reservation.customer_name == null ) {
                    result = true
                    this.error.message = 'Fill customer name';
                }

                 if (this.reservation.service_id == null ) {
                    result = true
                    this.error.message = 'Invalid Service';
                }

                

                 if (this.reservation.date == null || this.reservation.date == 'Invalid date') {
                    result = true
                    this.error.message = 'Invalid date';
                }

                this.error.status = true;
                return result;
            }
        },
        components: {
            VueTimepicker,
            Datepicker,
            'dropdown': dropDown
        },
    }
</script>

<style>

label {
    margin-top: 10px;
}

label.col-12 {
    padding-left: 0px;
}
.time-picker .dropdown ul li.active, 
.time-picker .dropdown ul li.active:hover {
    background: #17a2b8;
    color: #fff;
}

.card {
    margin-bottom: 30px;
    padding-bottom: 15px;
}

.messages{
        padding-top: 10px;
}

</style>


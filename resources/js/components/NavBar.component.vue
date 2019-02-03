<template>
    <div class="row">
        <div class="col-12">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                        <a class="navbar-brand" href="#">{{navOptions.title}}</a>
                        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                            <li class="nav-item " @click="activate(item.name)" v-bind:class="[item.name == itemActive ? 'active' : '' ]" v-for="(item, index) in navOptions.items" :key="index">
                                <a class="nav-link" href="#">{{item.name}} <span class="sr-only"></span></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</template>

<script>
    // componente para renderizar el navbar dinámicamente
    export default {
        name: 'nav-bar',
        props: ['navOptions', 'itemActive'],
        methods: {
            // emitimos el evento del item que ha sido seleccionado
            activate(item) {
                this.$emit('activeItem', item);
            }
        }
    }
</script>

<style scoped>

</style>



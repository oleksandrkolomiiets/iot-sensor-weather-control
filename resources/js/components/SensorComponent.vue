<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div v-if="loaded" class="card-body">
                    <div v-if="error">
                        Please, <a href="/sensor/configure">Reconfigure</a> you sensor. DATA is not up to date!
                    </div>
                    <div v-else>
                        <div style="padding: 10px;">
                            <div style="margin-bottom: 5px;">
                                <p>Temperature: {{ temperature }} ℃</p>
                                <vue-slider
                                    v-bind="optionsTemperature"
                                    v-model="temperature"
                                    @change="temperatureUpdated = false"
                                />
                                <button
                                    class="btn btn-primary"
                                    v-if="!temperatureUpdated"
                                    @click="updateTemperature"
                                >
                                    Apply
                                </button>
                            </div>
                            <div style="margin-bottom: 5px;">
                                <p>Humidity: {{ humidity }} %</p>
                                <vue-slider
                                    v-bind="optionsHumidity"
                                    v-model="humidity"
                                    @change="humidityUpdated = false"
                                />
                                <button
                                    class="btn btn-primary"
                                    v-if="!humidityUpdated"
                                    @click="updateHumidity"
                                >
                                    Apply
                                </button>
                            </div>
                        </div>
                        <div id="table">
                            <table style="margin: 0 auto;text-align: center;">
                                <tr>
                                    <th>Celsius (℃)</th>
                                    <th>Fahrenheit (℉)</th>
                                    <th>Humidity (%)</th>
                                    <th>Time</th>
                                </tr>
                                <tr v-for="sensor in sensorData">
                                    <td>{{ sensor.last_celsius }}</td>
                                    <td>{{ sensor.last_fahrenheit }}</td>
                                    <td>{{ sensor.last_humidity }}</td>
                                    <td>{{ sensor.updated_at }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VueSlider from 'vue-slider-component'
    import 'vue-slider-component/theme/antd.css'

    export default {
        name: 'SensorComponent',
        components: {
            VueSlider,
        },
        data: () => ({
            sensorData: [],
            optionsTemperature: {
                min: 0,
                max: 50,
            },
            optionsHumidity: {
                min: 0,
                max: 100,
            },
            temperature: 0,
            humidity: 0,
            temperatureUpdated: true,
            humidityUpdated: true,
            error: false,
            loaded: false,
        }),
        mounted() {
            this.fetch();
        },
        methods: {
            fetch() {
                axios.get('/sensor/fetch')
                    .then(({data}) => {
                        if (!data.address) {
                            this.error = true;
                        }

                        if (this.sensorData.length > 4) {
                            this.sensorData.shift();
                        }

                        this.sensorData.push(data);

                        if (!this.loaded) {
                            this.temperature = data.last_celsius;
                            this.humidity = data.last_humidity;
                        }
                    })
                    .finally(() => {
                        this.loaded = true;
                    })

                setTimeout(() => {
                    this.fetch();
                }, 10000);
            },
            updateTemperature() {
                axios.post('/sensor/update/temperature', {value: this.temperature})
                    .then(() => {
                        this.temperatureUpdated = true;
                    })

            },
            updateHumidity() {
                axios.post('/sensor/update/humidity', {value: this.humidity})
                    .then(() => {
                        this.humidityUpdated = true;
                    })
            },
        }
    }
</script>

<style>
    table, th, td {
        border: 1px solid black;
        padding: 1em;
        border-collapse: collapse;
    }

    #table {
        width: 100%;
        max-height: 50vh;
        overflow-y: auto;
    }
</style>

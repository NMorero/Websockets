<template>
    <input
    @keyup.enter="sendBackground"
    name="message"
    v-model="newBackground"
     type="text">
</template>

<script>
import changeBackground from './components/ChatsComponent.vue';
export default {

    data(){
        return {
            background: '',
            newBackground: '',
        }
    },

    created() {
        Echo.join('chat')
        .listen('ChangeBackground', (event) => {
            this.background = event.message;
            console.log(event.message);
        });
    },

    methods: {
        sendBackground(){
            axios.post('newBackground', {message: this.newBackground});
            this.newBackground = '';
        }
    },
    components: {
    changeBackground,
  },
}
</script>

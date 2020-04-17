
<template>

   <div class="row justify-content-center">
       <select name="background" id="background" class="form-control my-2" @change="changeBackground" v-model="key">
           <option value="/waiting" selected>Waiting..</option>
           <option value="http://archelier.com//ai/index.html">1</option>

       </select>
       <iframe v-if="admin == 1 || admin == 2" v-bind:src="url"  frameborder="0" width="720px" height="420px"></iframe>
        <iframe v-else v-bind:src="url"  frameborder="0" style="position:fixed; top:0px; left:0px; bottom:0px; right:0px; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:1;"></iframe>
       <!-- <img v-bind:src="'/upload/' + url + '.jpg'" width="800px" /> -->
       <!-- <div class="col-8">
           <div class="card card-default">
               <div class="card-header">Messages ee</div>
               <div class="card-body p-0">
                   <ul class="list-unstyled" style="height:300px; overflow-y:scroll" v-chat-scroll>
                       <li class="p-2" v-for="(message, index) in messages" :key="index" >
                           <strong>{{ message.user.name }}</strong>
                           {{ message.message }}
                       </li>
                   </ul>
               </div>

               <input
                    @keydown="sendTypingEvent"
                    @keyup.enter="sendMessage"
                    v-model="newMessage"
                    type="text"
                    name="message"
                    placeholder="Enter your message..."
                    class="form-control">
           </div>
            <span class="text-muted" v-if="activeUser" >{{ activeUser.name }} is typing...</span>
       </div>

        <div class="col-4">
            <div class="card card-default">
                <div class="card-header">Active Users</div>
                <div class="card-body">
                    <ul>
                        <li class="py-2" v-for="(user, index) in users" :key="index">
                            {{ user.name }}
                        </li>
                    </ul>
                </div>
            </div>

        </div> -->

   </div>
</template>

<script>

    var admin = document.getElementById('admin').value;
    console.log(admin);
    export default {

        props:['user'],

        data() {
            return {
                admin: admin,
                key: '',
                messages: [],
                background: '',
                newMessage: '',
                users:[],
                url: '/waiting',
                activeUser: false,
                typingTimer: false,
            }
        },

        created() {
            this.fetchMessages();

            Echo.join('chat')
                .here(user => {

                    this.users = user;
                })
                .joining(user => {
                    this.users.push(user);
                })
                .leaving(user => {
                    this.users = this.users.filter(u => u.id != user.id);
                })
                .listen('MessageSent',(event) => {
                    this.messages.push(event.message);
                })
                .listen('ChangeBackground', (event) => {
                    this.url = event.message.message;
                    console.log('Cambio: ' + event.message.message);
                })
                .listenForWhisper('typing', user => {
                   this.activeUser = user;

                    if(this.typingTimer) {
                        clearTimeout(this.typingTimer);
                    }

                   this.typingTimer = setTimeout(() => {
                       this.activeUser = false;
                   }, 3000);
                })

        },

        methods: {
            changeBackground(){
                this.url = this.key
                console.log(this.key);
                axios.post('newBackground', {message: this.key});
            },
            fetchMessages() {
                axios.get('messages').then(response => {
                    this.messages = response.data;
                })
            },

            sendMessage() {

                this.messages.push({
                    user: this.user,
                    message: this.newMessage
                });

                axios.post('messages', {message: this.newMessage});

                this.newMessage = '';
            },

            sendTypingEvent() {
                Echo.join('chat')
                    .whisper('typing', this.user);
            },

            changeBackgound(color) {
                axios.post('changeBackgound', {message: color});
            }
        }
    }
</script>

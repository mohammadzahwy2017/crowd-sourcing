<template>
    <li class="dropdown" >
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
        <i class="fa fa-bell"></i> <!-- Notification Icon -->
        <span class="badge badge-light">{{ unreadNotifications.length }}</span> <!-- Notification Counter -->
        </a>
        <ul class="dropdown-menu" role="menu">
            <li v-for="unread in unreadNotifications" :key="unread.id">
                <a :href="threadURL"  @click="markNotificationasRead(unread)">
                    {{unread.data.data}}
                </a>
            </li>
        </ul>   
    </li>
</template>


<script>
    
    export default {
        props:['unreads','userid'],
        data(){
            return {
                unreadNotifications:this.unreads,
                threadURL:""
            }
        },
        methods:{
            markNotificationasRead(notification){
                if(this.unreadNotifications.length > 0){
                    axios.post('/markAsRead',{id:notification.id})
                    .then(res => {
                        this.unreadNotifications.splice(notification,1);
                    });
                }
            }
        },
        mounted(){
            console.log('Component mounted.');
            this.threadURL="#";
            Echo.private('App.User.' + this.userid)
            .notification((notification) => {
                let newUnreadNotification={data:{data:notification.data,user:notification.data.user},id:notification.id};
                this.unreadNotifications.push(newUnreadNotification);
            });
        }
    }
</script>

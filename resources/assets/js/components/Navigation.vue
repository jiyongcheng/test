<template>
    <nav class="navbar">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">Home</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="/pets">Mypal</a></li>
                    <li><a href="/user/signup">signup</a></li>
                    <li><a href="/user/signin">signin</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>
</template>


<script>
    export default {
        mounted() {
        }
    }
</script>
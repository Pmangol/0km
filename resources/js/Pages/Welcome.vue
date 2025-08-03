<template>
    <div class="bg-blue-700 text-white min-h-screen flex flex-col items-center justify-center relative">
        <!-- Login/Register links -->
        <nav class="absolute top-0 right-0 mt-6 mr-8 flex gap-4">
            <a href="/login" class="text-white hover:underline font-semibold">Iniciar sesión</a>
            <a href="/register" class="text-white hover:underline font-semibold">Registrarse</a>
        </nav>
        <img
            id="background"
            class="mx-auto mt-12 mb-8 w-48 h-48 object-contain rounded-full shadow-lg border-4 border-white"
            src="/images/logo-okm.png"
            alt="Logo OKM.APP"
        />
        <div class="text-center max-w-xl px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Impulsa tu negocio con OKM.APP</h1>
            <p class="text-lg md:text-xl mb-8">Somos una agencia digital creativa. Creamos sitios web, tiendas en línea y estrategias de marketing para que tu marca crezca en internet.</p>
        </div>
        <!-- Contact Form -->
        <form id="contacto" class="bg-white/90 rounded-lg shadow-lg p-8 w-full max-w-md text-blue-900 mt-8" @submit.prevent="submitContact">
            <h2 class="text-2xl font-bold mb-4 text-center">Contáctanos</h2>
            <div v-if="success" class="mb-4 p-3 rounded bg-green-100 text-green-800 text-center">¡Mensaje enviado correctamente!</div>
            <div v-if="error" class="mb-4 p-3 rounded bg-red-100 text-red-800 text-center">{{ error }}</div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold" for="nombre">Nombre</label>
                <input v-model="nombre" type="text" id="nombre" name="nombre" class="w-full px-3 py-2 rounded border border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400" required />
                <div v-if="errors.nombre" class="text-red-600 text-sm mt-1">{{ errors.nombre[0] }}</div>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold" for="email">Email</label>
                <input v-model="email" type="email" id="email" name="email" class="w-full px-3 py-2 rounded border border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400" required />
                <div v-if="errors.email" class="text-red-600 text-sm mt-1">{{ errors.email[0] }}</div>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold" for="mensaje">Mensaje</label>
                <textarea v-model="mensaje" id="mensaje" name="mensaje" rows="4" class="w-full px-3 py-2 rounded border border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400" required></textarea>
                <div v-if="errors.mensaje" class="text-red-600 text-sm mt-1">{{ errors.mensaje[0] }}</div>
            </div>
            <button type="submit" class="w-full bg-blue-700 text-white font-semibold py-2 rounded hover:bg-blue-800 transition">Enviar mensaje</button>
        </form>
        <footer class="mt-16 text-center text-sm text-white/80">
            &copy; 2025 OKM.APP Agencia Digital
        </footer>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const nombre = ref('');
const email = ref('');
const mensaje = ref('');
const success = ref(false);
const error = ref('');
const errors = ref({});

async function submitContact() {
    success.value = false;
    error.value = '';
    errors.value = {};
    try {
        const response = await fetch('/contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ nombre: nombre.value, email: email.value, mensaje: mensaje.value })
        });
        if (response.ok) {
            nombre.value = '';
            email.value = '';
            mensaje.value = '';
            success.value = true;
        } else if (response.status === 422) {
            const data = await response.json();
            errors.value = data.errors || {};
        } else {
            error.value = 'Ocurrió un error al enviar el mensaje.';
        }
    } catch (e) {
        error.value = 'No se pudo conectar con el servidor.';
    }
}
</script>

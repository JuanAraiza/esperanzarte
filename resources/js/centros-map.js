import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import iconRetinaUrl from 'leaflet/dist/images/marker-icon-2x.png';
import iconUrl from 'leaflet/dist/images/marker-icon.png';
import shadowUrl from 'leaflet/dist/images/marker-shadow.png';

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({ iconRetinaUrl, iconUrl, shadowUrl });

const modal = document.getElementById('exampleModal');
const mapaElemento = document.getElementById('mapaNuevoCentro');
const latitudInput = document.getElementById('latitud');
const longitudInput = document.getElementById('longitud');
const coordenadasTexto = document.getElementById('coordenadasCentro');
let mapa;
let marcador;

function establecerUbicacion(latitud, longitud) {
    const ubicacion = [latitud, longitud];

    if (marcador) {
        marcador.setLatLng(ubicacion);
    } else {
        marcador = L.marker(ubicacion, { draggable: true }).addTo(mapa);
        marcador.on('dragend', () => {
            const nuevaUbicacion = marcador.getLatLng();
            establecerUbicacion(nuevaUbicacion.lat, nuevaUbicacion.lng);
        });
    }

    latitudInput.value = latitud.toFixed(7);
    longitudInput.value = longitud.toFixed(7);
    coordenadasTexto.textContent = `Latitud: ${latitudInput.value}, longitud: ${longitudInput.value}`;
}

if (modal && mapaElemento && latitudInput && longitudInput && coordenadasTexto) {
    modal.addEventListener('shown.bs.modal', () => {
        if (mapa) {
            mapa.invalidateSize();
            return;
        }

        mapa = L.map(mapaElemento).setView([20.5202873, -100.8153534], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 24,
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(mapa);
        mapa.on('click', (evento) => establecerUbicacion(evento.latlng.lat, evento.latlng.lng));
    });
}

document.querySelectorAll('.modal').forEach((modalEditar) => {
    const mapaEditarElemento = modalEditar.querySelector('.mapa-editar-centro');
    const latitudEditarInput = modalEditar.querySelector('.latitud-editar-centro');
    const longitudEditarInput = modalEditar.querySelector('.longitud-editar-centro');
    const coordenadasEditarTexto = modalEditar.querySelector('.coordenadas-editar-centro');
    let mapaEditar;
    let marcadorEditar;

    if (!mapaEditarElemento || !latitudEditarInput || !longitudEditarInput || !coordenadasEditarTexto) return;

    function establecerUbicacionEditar(latitud, longitud) {
        const ubicacion = [latitud, longitud];

        if (marcadorEditar) {
            marcadorEditar.setLatLng(ubicacion);
        } else {
            marcadorEditar = L.marker(ubicacion, { draggable: true }).addTo(mapaEditar);
            marcadorEditar.on('dragend', () => {
                const nuevaUbicacion = marcadorEditar.getLatLng();
                establecerUbicacionEditar(nuevaUbicacion.lat, nuevaUbicacion.lng);
            });
        }

        latitudEditarInput.value = latitud.toFixed(7);
        longitudEditarInput.value = longitud.toFixed(7);
        coordenadasEditarTexto.textContent = `Latitud: ${latitudEditarInput.value}, longitud: ${longitudEditarInput.value}`;
    }

    modalEditar.addEventListener('shown.bs.modal', () => {
        if (mapaEditar) {
            mapaEditar.invalidateSize();
            return;
        }

        const latitud = Number.parseFloat(mapaEditarElemento.dataset.latitud);
        const longitud = Number.parseFloat(mapaEditarElemento.dataset.longitud);
        const tieneCoordenadas = Number.isFinite(latitud) && Number.isFinite(longitud);
        const ubicacionInicial = tieneCoordenadas ? [latitud, longitud] : [20.5202873, -100.8153534];

        mapaEditar = L.map(mapaEditarElemento).setView(ubicacionInicial, tieneCoordenadas ? 16 : 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 24,
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(mapaEditar);
        mapaEditar.on('click', (evento) => establecerUbicacionEditar(evento.latlng.lat, evento.latlng.lng));
        establecerUbicacionEditar(...ubicacionInicial);
    });
});
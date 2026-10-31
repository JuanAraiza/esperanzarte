console.log('aqui');
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import iconRetinaUrl from 'leaflet/dist/images/marker-icon-2x.png';
import iconUrl from 'leaflet/dist/images/marker-icon.png';
import shadowUrl from 'leaflet/dist/images/marker-shadow.png';

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
	iconRetinaUrl,
	iconUrl,
	shadowUrl,
});

function escaparHtml(valor) {
	const contenedor = document.createElement('div');
	contenedor.textContent = valor;
	return contenedor.innerHTML;
}

// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
	const mapaElemento = document.getElementById('mapaAsistencias');
	const mapaSinDatos = document.getElementById('mapaSinDatos');
	const datosElemento = document.getElementById('chartsData');
	const datos = JSON.parse(datosElemento?.textContent || '{}');
	const centros = datos.mapaCentros || [];

	if (mapaElemento && mapaSinDatos) {
		if (centros.length > 0) {
			const mapa = L.map(mapaElemento);
			const marcadores = [];

			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				maxZoom: 24,
				attribution: '&copy; OpenStreetMap contributors',
			}).addTo(mapa);

			centros.forEach((centro) => {
				const id = escaparHtml(centro.id);
				const nombre = escaparHtml(centro.centro);
				const direccion = escaparHtml(centro.direccion);
				const telefono = escaparHtml(centro.telefono);
				console.log(centro.latitud + ', ' + centro.longitud);
				const marcador = L.marker([centro.latitud, centro.longitud])
					.bindPopup(`<strong>${nombre}</strong><br>${direccion}<br>Teléfono: ${telefono}`)
					.addTo(mapa);
				marcadores.push(marcador);
			});

			mapa.fitBounds(L.featureGroup(marcadores).getBounds(), { padding: [30, 30], maxZoom: 24 });
		} else {
			mapaElemento.classList.add('d-none');
			mapaSinDatos.classList.remove('d-none');
		}
	}
});
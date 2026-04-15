<script setup>
import { computed } from 'vue';

const props = defineProps({
    placa: {
        type: String,
        default: '',
    },
    cidadeUf: {
        type: String,
        default: '',
    },
    tipoVeiculo: {
        type: String,
        default: '',
    },
    placaDianteira: {
        type: [Number, String],
        default: 0,
    },
    placaTraseira: {
        type: [Number, String],
        default: 0,
    },
    placaSegundaTraseira: {
        type: [Number, String],
        default: 0,
    },
    size: {
        type: String,
        default: 'compact',
    },
    showLocation: {
        type: Boolean,
        default: true,
    },
});

const placaNormalizada = computed(() =>
    String(props.placa || '')
        .replace(/[^a-zA-Z0-9]/g, '')
        .toUpperCase()
        .slice(0, 7) || '---0---',
);

const tipoDescricao = computed(() => String(props.tipoVeiculo || '').toUpperCase());

const isMoto = computed(() =>
    ['MOTO', 'MOTOCICLETA', 'MOTONETA', 'CICLOMOTOR', 'TRICICLO'].some((item) =>
        tipoDescricao.value.includes(item),
    ),
);

const showPair = computed(() =>
    !isMoto.value &&
    Number(props.placaDianteira || 0) === 1 &&
    (Number(props.placaTraseira || 0) === 1 || Number(props.placaSegundaTraseira || 0) === 1),
);

const placaTopoMoto = computed(() => placaNormalizada.value.slice(0, 3));
const placaBaseMoto = computed(() => placaNormalizada.value.slice(3));

const sizeVars = computed(() => {
    if (props.size === 'modal') {
        return {
            '--plate-car-width': '21rem',
            '--plate-moto-width': '13rem',
            '--plate-car-font': '2.9rem',
            '--plate-moto-top-font': '3rem',
            '--plate-moto-bottom-font': '3.16rem',
            '--plate-location-font': '0.92rem',
            '--plate-badge-font': '0.72rem',
            '--plate-stack-offset-x': '0.8rem',
            '--plate-stack-offset-y': '0.45rem',
        };
    }

    return {
        '--plate-car-width': '10.6rem',
        '--plate-moto-width': '8.1rem',
        '--plate-car-font': '1.64rem',
        '--plate-moto-top-font': '1.8rem',
        '--plate-moto-bottom-font': '1.96rem',
        '--plate-location-font': '0.74rem',
        '--plate-badge-font': '0.62rem',
        '--plate-stack-offset-x': '0.38rem',
        '--plate-stack-offset-y': '0.24rem',
    };
});

const tipoPlacaLabel = computed(() => {
    if (isMoto.value) {
        return 'Moto';
    }

    if (showPair.value) {
        return 'Par';
    }

    return 'Unitária';
});
</script>

<template>
    <div class="plate-preview" :style="sizeVars">
        <div v-if="isMoto" class="plate-shell plate-shell--moto">
            <div class="plate-shell__header">
                <span class="plate-shell__mercosul">MERCOSUL</span>
                <span class="plate-shell__country">BRASIL</span>
                <span class="plate-shell__flag" />
            </div>

            <span class="plate-shell__qr" />
            <span class="plate-shell__br">BR</span>

            <div class="plate-shell__text plate-shell__text--moto">
                <span class="plate-shell__text-top">{{ placaTopoMoto }}</span>
                <span class="plate-shell__text-bottom">{{ placaBaseMoto }}</span>
            </div>
        </div>

        <div v-else-if="showPair" class="plate-stack">
            <div class="plate-shell plate-shell--car plate-shell--back">
                <div class="plate-shell__header">
                    <span class="plate-shell__mercosul">MERCOSUL</span>
                    <span class="plate-shell__country">BRASIL</span>
                    <span class="plate-shell__flag" />
                </div>

                <span class="plate-shell__qr" />
                <span class="plate-shell__br">BR</span>
                <div class="plate-shell__text plate-shell__text--car">
                    {{ placaNormalizada }}
                </div>
            </div>

            <div class="plate-shell plate-shell--car plate-shell--front">
                <div class="plate-shell__header">
                    <span class="plate-shell__mercosul">MERCOSUL</span>
                    <span class="plate-shell__country">BRASIL</span>
                    <span class="plate-shell__flag" />
                </div>

                <span class="plate-shell__qr" />
                <span class="plate-shell__br">BR</span>
                <div class="plate-shell__text plate-shell__text--car">
                    {{ placaNormalizada }}
                </div>
            </div>
        </div>

        <div v-else class="plate-shell plate-shell--car">
            <div class="plate-shell__header">
                <span class="plate-shell__mercosul">MERCOSUL</span>
                <span class="plate-shell__country">BRASIL</span>
                <span class="plate-shell__flag" />
            </div>

            <span class="plate-shell__qr" />
            <span class="plate-shell__br">BR</span>
            <div class="plate-shell__text plate-shell__text--car">
                {{ placaNormalizada }}
            </div>
        </div>

        <span class="plate-preview__badge">
            {{ tipoPlacaLabel }}
        </span>

        <p v-if="showLocation" class="plate-preview__location">
            {{ cidadeUf || 'Local não informado' }}
        </p>
    </div>
</template>

<style scoped>
.plate-preview {
    display: flex;
    width: 100%;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
}

.plate-stack {
    position: relative;
    width: calc(var(--plate-car-width) + var(--plate-stack-offset-x));
    padding-bottom: var(--plate-stack-offset-y);
}

.plate-shell {
    position: relative;
    overflow: hidden;
    border: 3px solid #111827;
    background: linear-gradient(180deg, #ffffff 0%, #f7fafc 100%);
    box-shadow:
        0 12px 24px -20px rgba(15, 23, 42, 0.7),
        inset 0 0 0 1px rgba(15, 23, 42, 0.05);
}

.plate-shell--car {
    width: var(--plate-car-width);
    aspect-ratio: 2.82 / 1.14;
    border-radius: 0.72rem;
}

.plate-shell--moto {
    width: var(--plate-moto-width);
    aspect-ratio: 0.96 / 1;
    border-radius: 0.48rem;
}

.plate-shell--back,
.plate-shell--front {
    position: absolute;
    inset: 0;
}

.plate-shell--back {
    transform: translate(var(--plate-stack-offset-x), var(--plate-stack-offset-y));
    opacity: 0.98;
}

.plate-shell--front {
    position: relative;
}

.plate-shell__header {
    position: absolute;
    inset: 0 0 auto;
    height: 24%;
    background: linear-gradient(180deg, #1545b5 0%, #1b63df 100%);
}

.plate-shell__mercosul {
    position: absolute;
    left: 6%;
    top: 48%;
    transform: translateY(-50%);
    font-size: 0.33rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    color: rgba(255, 255, 255, 0.88);
}

.plate-shell__country {
    position: absolute;
    left: 50%;
    top: 49%;
    transform: translate(-50%, -50%);
    font-size: 0.66rem;
    font-weight: 900;
    letter-spacing: 0.18em;
    color: #ffffff;
}

.plate-shell__flag {
    position: absolute;
    right: 5%;
    top: 18%;
    width: 10%;
    height: 52%;
    border-radius: 0.2rem;
    border: 1px solid rgba(255, 255, 255, 0.72);
    background:
        radial-gradient(circle at center, #1d4ed8 0 18%, transparent 19%),
        linear-gradient(135deg, transparent 32%, #facc15 33% 66%, transparent 67%),
        #16a34a;
}

.plate-shell__qr {
    position: absolute;
    left: 6.5%;
    top: 31%;
    width: 10%;
    aspect-ratio: 1;
    border-radius: 0.16rem;
    border: 1px solid rgba(15, 23, 42, 0.12);
    background:
        linear-gradient(90deg, rgba(15, 23, 42, 0.18) 50%, transparent 50%) 0 0 / 0.18rem 0.18rem,
        linear-gradient(rgba(15, 23, 42, 0.18) 50%, transparent 50%) 0 0 / 0.18rem 0.18rem,
        #f8fafc;
}

.plate-shell__br {
    position: absolute;
    left: 6.5%;
    bottom: 8%;
    font-family: 'Arial Black', 'Bahnschrift', sans-serif;
    font-size: 0.56rem;
    font-weight: 900;
    color: #111827;
}

.plate-shell__text {
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #050505;
    font-family: 'Arial Black', 'Bahnschrift', 'Arial Narrow', sans-serif;
    font-weight: 900;
    text-transform: uppercase;
    text-rendering: geometricPrecision;
}

.plate-shell__text--car {
    inset: 28% 13% 10% 16%;
    font-size: var(--plate-car-font);
    letter-spacing: 0;
}

.plate-shell__text--moto {
    inset: 24% 8% 7.5% 8%;
    flex-direction: column;
    line-height: 0.84;
    letter-spacing: 0;
    font-family: 'Arial Narrow', 'Aptos Narrow', 'Bahnschrift', 'Arial Black', sans-serif;
    font-weight: 800;
}

.plate-shell__text-top {
    display: block;
    width: 100%;
    font-size: var(--plate-moto-top-font);
    text-align: center;
    transform: scaleX(1.02);
    transform-origin: center;
}

.plate-shell__text-bottom {
    display: block;
    width: 100%;
    font-size: var(--plate-moto-bottom-font);
    text-align: center;
    transform: scaleX(0.96);
    transform-origin: center;
}

.plate-preview__badge {
    margin-top: 0.65rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 999px;
    border: 1px solid #dbe4f0;
    background: #ffffff;
    padding: 0.34rem 0.72rem;
    font-size: var(--plate-badge-font);
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #64748b;
}

.plate-preview__location {
    margin-top: 0.8rem;
    text-align: center;
    font-size: var(--plate-location-font);
    font-weight: 700;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: #64748b;
}

.plate-shell--moto .plate-shell__header {
    height: 24%;
}

.plate-shell--moto .plate-shell__mercosul {
    left: 5.5%;
    font-size: 0.28rem;
    letter-spacing: 0.1em;
}

.plate-shell--moto .plate-shell__country {
    top: 48%;
    font-size: 0.82rem;
    letter-spacing: 0.14em;
}

.plate-shell--moto .plate-shell__flag {
    width: 13%;
    height: 40%;
    top: 15%;
}

.plate-shell--moto .plate-shell__qr {
    top: 31%;
    width: 11%;
}

.plate-shell--moto .plate-shell__br {
    bottom: 6.5%;
    font-size: 0.62rem;
}

</style>

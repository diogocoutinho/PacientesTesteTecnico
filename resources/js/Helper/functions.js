export const formatCpf = (value) => {
    return value.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
}

export const formatDate = (value) => {
    return new Intl.DateTimeFormat('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    }).format(new Date(value));
}

export const back = () => {
    window.history.back();
}

export const formatPostalCode = (value) => {
    return value.replace(/^(\d{5})(\d{3})/, '$1-$2');
}

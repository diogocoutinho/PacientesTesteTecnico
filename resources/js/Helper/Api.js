export const fetchPatients = async () => {
    const response = await axios.get(route('patients.getPatients'));
    return response.data;
}

export const searchAddress = async (cep) => {
    const response = await axios.get(route('patients.search-cep', cep));
    return response.data;
}

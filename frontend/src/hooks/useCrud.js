import { useState, useEffect, useCallback } from 'react';
import useApi from './useApi';

export const useCrud = (endpoint, options = {}) => {
  const { get, post, put, delete: del, loading, error } = useApi();
  const [data, setData] = useState([]);
  const [searchTerm, setSearchTerm] = useState('');
  const [filters, setFilters] = useState({});
  const [pagination, setPagination] = useState({
    current_page: 1,
    last_page: 1,
    per_page: 15,
    total: 0,
  });

  const fetchData = useCallback(async () => {
    try {
      const params = {
        search: searchTerm,
        ...filters,
        page: pagination.current_page,
        per_page: pagination.per_page,
      };

      const response = await get(endpoint, params);
      
      if (response.data) {
        setData(response.data);
        if (response.pagination) {
          setPagination(response.pagination);
        }
      } else {
        setData(response);
      }
    } catch (err) {
      console.error('Error fetching data:', err);
    }
  }, [endpoint, searchTerm, filters, pagination.current_page, pagination.per_page, get]);

  useEffect(() => {
    fetchData();
  }, [fetchData]);

  const create = useCallback(async (itemData) => {
    try {
      const response = await post(endpoint, itemData);
      await fetchData(); // Refresh data
      return response;
    } catch (err) {
      throw err;
    }
  }, [endpoint, post, fetchData]);

  const update = useCallback(async (id, itemData) => {
    try {
      const response = await put(`${endpoint}/${id}`, itemData);
      await fetchData(); // Refresh data
      return response;
    } catch (err) {
      throw err;
    }
  }, [endpoint, put, fetchData]);

  const remove = useCallback(async (id) => {
    try {
      const response = await del(`${endpoint}/${id}`);
      await fetchData(); // Refresh data
      return response;
    } catch (err) {
      throw err;
    }
  }, [endpoint, del, fetchData]);

  const handleSearch = useCallback((term) => {
    setSearchTerm(term);
    setPagination(prev => ({ ...prev, current_page: 1 }));
  }, []);

  const handleFilter = useCallback((newFilters) => {
    setFilters(newFilters);
    setPagination(prev => ({ ...prev, current_page: 1 }));
  }, []);

  const handlePageChange = useCallback((page) => {
    setPagination(prev => ({ ...prev, current_page: page }));
  }, []);

  const handlePerPageChange = useCallback((perPage) => {
    setPagination(prev => ({ ...prev, per_page: perPage, current_page: 1 }));
  }, []);

  return {
    data,
    loading,
    error,
    searchTerm,
    filters,
    pagination,
    fetchData,
    create,
    update,
    remove,
    handleSearch,
    handleFilter,
    handlePageChange,
    handlePerPageChange,
  };
};

export default useCrud;





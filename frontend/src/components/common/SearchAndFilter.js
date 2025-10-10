import React from 'react';
import {
  Box,
  TextField,
  Button,
  Grid,
  FormControl,
  InputLabel,
  Select,
  MenuItem,
  Chip,
  Stack,
} from '@mui/material';
import { Search as SearchIcon, Clear as ClearIcon } from '@mui/icons-material';

const SearchAndFilter = ({
  searchTerm = '',
  onSearchChange,
  filters = {},
  onFilterChange,
  onClearFilters,
  filterOptions = [],
  searchPlaceholder = 'Tìm kiếm...',
  showClearButton = true,
}) => {
  const handleSearchChange = (e) => {
    onSearchChange(e.target.value);
  };

  const handleFilterChange = (filterKey, value) => {
    onFilterChange({ ...filters, [filterKey]: value });
  };

  const handleClearFilters = () => {
    onSearchChange('');
    onClearFilters();
  };

  const hasActiveFilters = searchTerm || Object.values(filters).some(value => value !== '' && value !== null);

  return (
    <Box sx={{ mb: 3 }}>
      <Grid container spacing={2} alignItems="center">
        {/* Search Field */}
        <Grid item xs={12} md={6}>
          <TextField
            fullWidth
            variant="outlined"
            placeholder={searchPlaceholder}
            value={searchTerm}
            onChange={handleSearchChange}
            InputProps={{
              startAdornment: <SearchIcon sx={{ mr: 1, color: 'text.secondary' }} />,
            }}
          />
        </Grid>

        {/* Filter Fields */}
        {filterOptions.map((filter) => (
          <Grid item xs={12} sm={6} md={3} key={filter.key}>
            <FormControl fullWidth>
              <InputLabel>{filter.label}</InputLabel>
              <Select
                value={filters[filter.key] || ''}
                label={filter.label}
                onChange={(e) => handleFilterChange(filter.key, e.target.value)}
              >
                <MenuItem value="">
                  <em>Tất cả</em>
                </MenuItem>
                {filter.options.map((option) => (
                  <MenuItem key={option.value} value={option.value}>
                    {option.label}
                  </MenuItem>
                ))}
              </Select>
            </FormControl>
          </Grid>
        ))}

        {/* Clear Button */}
        {showClearButton && hasActiveFilters && (
          <Grid item xs={12} sm={6} md={3}>
            <Button
              variant="outlined"
              startIcon={<ClearIcon />}
              onClick={handleClearFilters}
              fullWidth
            >
              Xóa bộ lọc
            </Button>
          </Grid>
        )}
      </Grid>

      {/* Active Filters Display */}
      {hasActiveFilters && (
        <Box sx={{ mt: 2 }}>
          <Stack direction="row" spacing={1} flexWrap="wrap">
            {searchTerm && (
              <Chip
                label={`Tìm kiếm: "${searchTerm}"`}
                onDelete={() => onSearchChange('')}
                color="primary"
                variant="outlined"
              />
            )}
            {Object.entries(filters).map(([key, value]) => {
              if (!value) return null;
              const filter = filterOptions.find(f => f.key === key);
              const option = filter?.options.find(o => o.value === value);
              return (
                <Chip
                  key={key}
                  label={`${filter?.label}: ${option?.label || value}`}
                  onDelete={() => handleFilterChange(key, '')}
                  color="secondary"
                  variant="outlined"
                />
              );
            })}
          </Stack>
        </Box>
      )}
    </Box>
  );
};

export default SearchAndFilter;





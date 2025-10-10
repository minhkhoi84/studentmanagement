import React from 'react';
import {
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Paper,
  Typography,
  Box,
  CircularProgress,
  Alert,
} from '@mui/material';

const DataTable = ({
  columns = [],
  data = [],
  loading = false,
  error = null,
  emptyMessage = 'Không có dữ liệu',
  renderRow = null,
  ...props
}) => {
  if (loading) {
    return (
      <Box display="flex" justifyContent="center" alignItems="center" minHeight="200px">
        <CircularProgress />
      </Box>
    );
  }

  if (error) {
    return (
      <Alert severity="error" sx={{ mb: 2 }}>
        {error}
      </Alert>
    );
  }

  return (
    <TableContainer component={Paper} {...props}>
      <Table>
        <TableHead>
          <TableRow>
            {columns.map((column, index) => (
              <TableCell
                key={index}
                align={column.align || 'left'}
                sx={{ fontWeight: 'bold', backgroundColor: 'grey.100' }}
              >
                {column.label}
              </TableCell>
            ))}
          </TableRow>
        </TableHead>
        <TableBody>
          {data.length === 0 ? (
            <TableRow>
              <TableCell colSpan={columns.length} align="center" sx={{ py: 4 }}>
                <Box textAlign="center">
                  <Typography variant="body1" color="text.secondary">
                    {emptyMessage}
                  </Typography>
                </Box>
              </TableCell>
            </TableRow>
          ) : (
            data.map((row, index) => (
              <TableRow key={row.id || index} hover>
                {renderRow ? (
                  renderRow(row, index)
                ) : (
                  columns.map((column, colIndex) => (
                    <TableCell key={colIndex} align={column.align || 'left'}>
                      {column.render ? column.render(row) : row[column.field]}
                    </TableCell>
                  ))
                )}
              </TableRow>
            ))
          )}
        </TableBody>
      </Table>
    </TableContainer>
  );
};

export default DataTable;





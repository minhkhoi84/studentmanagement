import React, { useState } from 'react';
import {
  Container,
  Typography,
  Box,
  Button,
  Chip,
  Card,
  CardContent,
  Grid,
  TextField,
  TableCell
} from '@mui/material';
import {
  Add as AddIcon,
  Business as BusinessIcon
} from '@mui/icons-material';
import { useAuth } from '../contexts/AuthContext';
import { useCrud } from '../hooks';
import {
  DataTable,
  FormDialog,
  SearchAndFilter,
  NotificationSnackbar,
  ActionButtons
} from '../components/common';

const Departments = () => {
  const { isSuperAdmin } = useAuth();
  const [openDialog, setOpenDialog] = useState(false);
  const [editingDepartment, setEditingDepartment] = useState(null);
  const [snackbar, setSnackbar] = useState({ open: false, message: '', severity: 'success' });
  const [formData, setFormData] = useState({
    name: '',
    code: '',
    description: '',
    status: 'active'
  });

  // Use CRUD hook
  const {
    data: departments,
    loading,
    error,
    searchTerm,
    filters,
    handleSearch,
    handleFilter,
    create,
    update,
    remove
  } = useCrud('/departments');

  const handleOpenDialog = (deptData = null) => {
    if (deptData) {
      setEditingDepartment(deptData);
      setFormData({
        name: deptData.name || '',
        code: deptData.code || '',
        description: deptData.description || '',
        status: deptData.status || 'active'
      });
    } else {
      setEditingDepartment(null);
      setFormData({
        name: '',
        code: '',
        description: '',
        status: 'active'
      });
    }
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setOpenDialog(false);
    setEditingDepartment(null);
    setFormData({
      name: '',
      code: '',
      description: '',
      status: 'active'
    });
  };

  const handleSubmit = async () => {
    try {
      if (editingDepartment) {
        await update(editingDepartment.id, formData);
        setSnackbar({
          open: true,
          message: 'Khoa đã được cập nhật thành công!',
          severity: 'success'
        });
      } else {
        await create(formData);
        setSnackbar({
          open: true,
          message: 'Khoa đã được tạo thành công!',
          severity: 'success'
        });
      }
      handleCloseDialog();
    } catch (err) {
      setSnackbar({
        open: true,
        message: 'Có lỗi xảy ra: ' + err.message,
        severity: 'error'
      });
    }
  };

  const handleDelete = async (id, name) => {
    if (window.confirm(`Bạn có chắc chắn muốn xóa khoa "${name}"?`)) {
      try {
        await remove(id);
        setSnackbar({
          open: true,
          message: 'Khoa đã được xóa thành công!',
          severity: 'success'
        });
      } catch (err) {
        setSnackbar({
          open: true,
          message: 'Có lỗi xảy ra: ' + err.message,
          severity: 'error'
        });
      }
    }
  };

  // Filter data
  const filteredDepartments = departments.filter(dept => {
    const matchesStatus = !filters.status || dept.status === filters.status;
    return matchesStatus;
  });

  // Filter options
  const filterOptions = [
    {
      key: 'status',
      label: 'Trạng thái',
      options: [
        { value: 'active', label: 'Hoạt động' },
        { value: 'inactive', label: 'Không hoạt động' }
      ]
    }
  ];

  // Table columns
  const columns = [
    { label: '#', field: 'index', align: 'left' },
    { label: 'Mã khoa', field: 'code', align: 'left' },
    { label: 'Tên khoa', field: 'name', align: 'left' },
    { label: 'Mô tả', field: 'description', align: 'left' },
    { label: 'Trạng thái', field: 'status', align: 'left' },
    { label: 'Thao tác', field: 'actions', align: 'center' }
  ];

  // Custom row renderer
  const renderRow = (dept, index) => (
    <>
      <TableCell>{index + 1}</TableCell>
      <TableCell>
        <Chip label={dept.code} color="primary" variant="outlined" size="small" />
      </TableCell>
      <TableCell>
        <Box display="flex" alignItems="center">
          <BusinessIcon sx={{ mr: 1, color: 'primary.main' }} />
          <Typography variant="subtitle2" fontWeight="bold">
            {dept.name}
          </Typography>
        </Box>
      </TableCell>
      <TableCell>
        <Typography variant="body2" color="text.secondary">
          {dept.description || 'Chưa có mô tả'}
        </Typography>
      </TableCell>
      <TableCell>
        <Chip
          label={dept.status === 'active' ? 'Hoạt động' : 'Không hoạt động'}
          color={dept.status === 'active' ? 'success' : 'default'}
          size="small"
        />
      </TableCell>
      <TableCell align="center">
        {isSuperAdmin() ? (
          <ActionButtons
            onEdit={() => handleOpenDialog(dept)}
            onDelete={() => handleDelete(dept.id, dept.name)}
          />
        ) : (
          <Typography variant="body2" color="text.secondary">
            Không có quyền
          </Typography>
        )}
      </TableCell>
    </>
  );

  return (
    <Container maxWidth="lg" sx={{ mt: 4, mb: 4 }}>
      {/* Header */}
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={4}>
        <Box>
          <Typography variant="h4" component="h1" gutterBottom>
            <BusinessIcon sx={{ mr: 2, verticalAlign: 'middle' }} />
            Quản Lý Khoa
          </Typography>
          <Typography variant="subtitle1" color="text.secondary">
            Quản lý thông tin các khoa trong hệ thống
          </Typography>
        </Box>
        {isSuperAdmin() && (
          <Button
            variant="contained"
            color="primary"
            startIcon={<AddIcon />}
            onClick={() => handleOpenDialog()}
          >
            Thêm khoa mới
          </Button>
        )}
      </Box>

      {/* Search and Filters */}
      <Card sx={{ mb: 3 }}>
        <CardContent>
          <SearchAndFilter
            searchTerm={searchTerm}
            onSearchChange={handleSearch}
            filters={filters}
            onFilterChange={handleFilter}
            onClearFilters={() => handleFilter({})}
            filterOptions={filterOptions}
            searchPlaceholder="Tìm kiếm khoa..."
          />
        </CardContent>
      </Card>

      {/* Data Table */}
      <Card>
        <CardContent>
          <Box display="flex" justifyContent="space-between" alignItems="center" mb={2}>
            <Typography variant="h6">
              Danh sách khoa ({filteredDepartments.length} khoa)
            </Typography>
          </Box>
          
          <DataTable
            columns={columns}
            data={filteredDepartments}
            loading={loading}
            error={error}
            renderRow={renderRow}
            emptyMessage="Không tìm thấy khoa nào"
          />
        </CardContent>
      </Card>

      {/* Form Dialog */}
      <FormDialog
        open={openDialog}
        onClose={handleCloseDialog}
        onSubmit={handleSubmit}
        title={editingDepartment ? 'Chỉnh sửa khoa' : 'Thêm khoa mới'}
        submitText={editingDepartment ? 'Cập nhật' : 'Tạo mới'}
      >
        <Grid container spacing={2}>
          <Grid item xs={12}>
            <TextField
              fullWidth
              label="Tên khoa"
              required
              value={formData.name}
              onChange={(e) => setFormData({ ...formData, name: e.target.value })}
            />
          </Grid>
          <Grid item xs={12}>
            <TextField
              fullWidth
              label="Mã khoa"
              required
              value={formData.code}
              onChange={(e) => setFormData({ ...formData, code: e.target.value })}
            />
          </Grid>
          <Grid item xs={12}>
            <TextField
              fullWidth
              multiline
              rows={3}
              label="Mô tả"
              value={formData.description}
              onChange={(e) => setFormData({ ...formData, description: e.target.value })}
            />
          </Grid>
          <Grid item xs={12}>
            <TextField
              fullWidth
              select
              label="Trạng thái"
              value={formData.status}
              onChange={(e) => setFormData({ ...formData, status: e.target.value })}
            >
              <option value="active">Hoạt động</option>
              <option value="inactive">Không hoạt động</option>
            </TextField>
          </Grid>
        </Grid>
      </FormDialog>

      {/* Notification */}
      <NotificationSnackbar
        open={snackbar.open}
        message={snackbar.message}
        severity={snackbar.severity}
        onClose={() => setSnackbar({ ...snackbar, open: false })}
      />
    </Container>
  );
};

export default Departments;

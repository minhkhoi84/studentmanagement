import React, { useState, useEffect } from 'react';
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
  School as SchoolIcon,
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
import {
  FieldRow,
  fieldInputProps,
  dialogPaperProps,
  FormLayout
} from '../components/common/FormFieldRow';

const Classes = () => {
  const { isSuperAdmin } = useAuth();
  const [departments, setDepartments] = useState([]);
  const [openDialog, setOpenDialog] = useState(false);
  const [editingClass, setEditingClass] = useState(null);
  const [snackbar, setSnackbar] = useState({ open: false, message: '', severity: 'success' });
  const [formData, setFormData] = useState({
    name: '',
    class_code: '',
    department_id: '',
    max_students: '',
    status: 'active'
  });

  // Use CRUD hook for classes
  const {
    data: classes,
    loading,
    error,
    searchTerm,
    filters,
    handleSearch,
    handleFilter,
    create,
    update,
    remove
  } = useCrud('/classes');

  // Fetch departments
  useEffect(() => {
    const fetchDepartments = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/departments');
        if (!response.ok) throw new Error('Failed to fetch departments');
        const data = await response.json();
        setDepartments(data.data || data || []);
      } catch (err) {
        console.error('Error fetching departments:', err);
      }
    };
    fetchDepartments();
  }, []);

  const handleOpenDialog = (classData = null) => {
    if (classData) {
      setEditingClass(classData);
      setFormData({
        name: classData.name || '',
        class_code: classData.class_code || classData.code || '',
        department_id: classData.department_id || '',
        max_students: classData.max_students || '',
        status: classData.status || 'active'
      });
    } else {
      setEditingClass(null);
      setFormData({
        name: '',
        class_code: '',
        department_id: '',
        max_students: '',
        status: 'active'
      });
    }
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setOpenDialog(false);
    setEditingClass(null);
    setFormData({
      name: '',
      class_code: '',
      department_id: '',
      max_students: '',
      status: 'active'
    });
  };

  const handleSubmit = async () => {
    try {
      if (editingClass) {
        await update(editingClass.id, formData);
        setSnackbar({
          open: true,
          message: 'Lớp đã được cập nhật thành công!',
          severity: 'success'
        });
      } else {
        await create(formData);
        setSnackbar({
          open: true,
          message: 'Lớp đã được tạo thành công!',
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
    if (window.confirm(`Bạn có chắc chắn muốn xóa lớp "${name}"?`)) {
      try {
        await remove(id);
        setSnackbar({
          open: true,
          message: 'Lớp đã được xóa thành công!',
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

  // Filter data based on local filters
  const filteredClasses = classes.filter(classItem => {
    const matchesDepartment = !filters.department_id || classItem.department_id === parseInt(filters.department_id);
    const matchesStatus = !filters.status || classItem.status === filters.status;
    return matchesDepartment && matchesStatus;
  });

  // Filter options for SearchAndFilter component
  const filterOptions = [
    {
      key: 'department_id',
      label: 'Khoa',
      options: departments.map(dept => ({ value: dept.id, label: dept.name }))
    },
    {
      key: 'status',
      label: 'Trạng thái',
      options: [
        { value: 'active', label: 'Hoạt động' },
        { value: 'inactive', label: 'Không hoạt động' }
      ]
    }
  ];

  // Table columns configuration
  const columns = [
    { label: '#', field: 'index', align: 'left' },
    { label: 'Mã Lớp', field: 'code', align: 'left' },
    { label: 'Tên Lớp', field: 'name', align: 'left' },
    { label: 'Khoa', field: 'department', align: 'left' },
    { label: 'Trạng Thái', field: 'status', align: 'left' },
    { label: 'Ngày Tạo', field: 'created_at', align: 'left' },
    { label: 'Thao Tác', field: 'actions', align: 'center' }
  ];

  // Custom row renderer
  const renderRow = (classItem, index) => (
    <>
      <TableCell>{index + 1}</TableCell>
      <TableCell>
        <Chip label={classItem.code} color="primary" variant="outlined" size="small" />
      </TableCell>
      <TableCell>
        <Box display="flex" alignItems="center">
          <SchoolIcon sx={{ mr: 1, color: 'primary.main' }} />
          <Box>
            <Typography variant="subtitle2" fontWeight="bold">
              {classItem.name}
            </Typography>
            <Typography variant="caption" color="text.secondary">
              ID: #{classItem.id}
            </Typography>
          </Box>
        </Box>
      </TableCell>
      <TableCell>
        {classItem.department ? (
          <Chip
            label={classItem.department.name}
            color="info"
            variant="outlined"
            size="small"
            icon={<BusinessIcon />}
          />
        ) : (
          <Typography variant="body2" color="text.secondary">
            Chưa phân khoa
          </Typography>
        )}
      </TableCell>
      <TableCell>
        <Chip
          label={classItem.status === 'active' ? 'Hoạt động' : 'Không hoạt động'}
          color={classItem.status === 'active' ? 'success' : 'default'}
          size="small"
        />
      </TableCell>
      <TableCell>
        {new Date(classItem.created_at).toLocaleDateString('vi-VN')}
      </TableCell>
      <TableCell align="center">
        {isSuperAdmin() ? (
          <ActionButtons
            onEdit={() => handleOpenDialog(classItem)}
            onDelete={() => handleDelete(classItem.id, classItem.name)}
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
            <SchoolIcon sx={{ mr: 2, verticalAlign: 'middle' }} />
            Quản Lý Lớp
          </Typography>
          <Typography variant="subtitle1" color="text.secondary">
            Quản lý thông tin các lớp học trong hệ thống
          </Typography>
        </Box>
        {isSuperAdmin() && (
          <Button
            variant="contained"
            color="primary"
            startIcon={<AddIcon />}
            onClick={() => handleOpenDialog()}
          >
            Thêm lớp mới
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
            searchPlaceholder="Tìm kiếm lớp..."
          />
        </CardContent>
      </Card>

      {/* Data Table */}
      <Card>
        <CardContent>
          <Box display="flex" justifyContent="space-between" alignItems="center" mb={2}>
            <Typography variant="h6">
              Danh sách lớp ({filteredClasses.length} lớp)
            </Typography>
          </Box>
          
          <DataTable
            columns={columns}
            data={filteredClasses}
            loading={loading}
            error={error}
            renderRow={renderRow}
            emptyMessage="Không tìm thấy lớp nào"
          />
        </CardContent>
      </Card>

      {/* Form Dialog */}
      <FormDialog
        open={openDialog}
        onClose={handleCloseDialog}
        onSubmit={handleSubmit}
        title={editingClass ? 'Chỉnh sửa lớp' : 'Thêm lớp mới'}
        submitText={editingClass ? 'Cập nhật' : 'Tạo mới'}
        PaperProps={dialogPaperProps}
      >
        <FormLayout>
          <FieldRow label="Tên lớp" required>
            <TextField
              fullWidth
              {...fieldInputProps}
              value={formData.name}
              onChange={(e) => setFormData({ ...formData, name: e.target.value })}
            />
          </FieldRow>
          <FieldRow label="Mã lớp" required>
            <TextField
              fullWidth
              {...fieldInputProps}
              value={formData.class_code}
              onChange={(e) => setFormData({ ...formData, class_code: e.target.value })}
            />
          </FieldRow>
          <FieldRow label="Khoa">
            <TextField
              fullWidth
              {...fieldInputProps}
              select
              value={formData.department_id}
              onChange={(e) => setFormData({ ...formData, department_id: e.target.value })}
            >
              <option value="">Chọn khoa</option>
              {departments.map((dept) => (
                <option key={dept.id} value={dept.id}>
                  {dept.name}
                </option>
              ))}
            </TextField>
          </FieldRow>
          <FieldRow label="Số sinh viên tối đa">
            <TextField
              fullWidth
              {...fieldInputProps}
              type="number"
              value={formData.max_students}
              onChange={(e) => setFormData({ ...formData, max_students: e.target.value })}
            />
          </FieldRow>
          <FieldRow label="Trạng thái">
            <TextField
              fullWidth
              {...fieldInputProps}
              select
              value={formData.status}
              onChange={(e) => setFormData({ ...formData, status: e.target.value })}
            >
              <option value="active">Hoạt động</option>
              <option value="inactive">Không hoạt động</option>
            </TextField>
          </FieldRow>
        </FormLayout>
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

export default Classes;

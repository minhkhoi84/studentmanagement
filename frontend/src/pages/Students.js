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
  Avatar,
  TableCell
} from '@mui/material';
import {
  Add as AddIcon,
  Person as PersonIcon,
  Email as EmailIcon,
  Phone as PhoneIcon
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

const Students = () => {
  const { isSuperAdmin } = useAuth();
  const [openDialog, setOpenDialog] = useState(false);
  const [editingStudent, setEditingStudent] = useState(null);
  const [snackbar, setSnackbar] = useState({ open: false, message: '', severity: 'success' });
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    address: '',
    student_code: '',
    class: '',
    status: 'active'
  });

  // Use CRUD hook
  const {
    data: students,
    loading,
    error,
    searchTerm,
    filters,
    handleSearch,
    handleFilter,
    create,
    update,
    remove
  } = useCrud('/students');

  const handleOpenDialog = (studentData = null) => {
    if (studentData) {
      setEditingStudent(studentData);
      setFormData({
        name: studentData.name || '',
        email: studentData.email || '',
        phone: studentData.phone || '',
        address: studentData.address || '',
        student_code: studentData.student_code || '',
        class: studentData.class || '',
        status: studentData.status || 'active'
      });
    } else {
      setEditingStudent(null);
      setFormData({
        name: '',
        email: '',
        phone: '',
        address: '',
        student_code: '',
        class: '',
        status: 'active'
      });
    }
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setOpenDialog(false);
    setEditingStudent(null);
    setFormData({
      name: '',
      email: '',
      phone: '',
      address: '',
      student_code: '',
      class: '',
      status: 'active'
    });
  };

  const handleSubmit = async () => {
    try {
      if (editingStudent) {
        await update(editingStudent.id, formData);
        setSnackbar({
          open: true,
          message: 'Sinh viên đã được cập nhật thành công!',
          severity: 'success'
        });
      } else {
        await create(formData);
        setSnackbar({
          open: true,
          message: 'Sinh viên đã được tạo thành công!',
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
    if (window.confirm(`Bạn có chắc chắn muốn xóa sinh viên "${name}"?`)) {
      try {
        await remove(id);
        setSnackbar({
          open: true,
          message: 'Sinh viên đã được xóa thành công!',
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
  const filteredStudents = students.filter(student => {
    const matchesStatus = !filters.status || student.status === filters.status;
    return matchesStatus;
  });

  // Filter options
  const filterOptions = [
    {
      key: 'status',
      label: 'Trạng thái',
      options: [
        { value: 'active', label: 'Hoạt động' },
        { value: 'inactive', label: 'Không hoạt động' },
        { value: 'graduated', label: 'Đã tốt nghiệp' }
      ]
    }
  ];

  // Table columns
  const columns = [
    { label: '#', field: 'index', align: 'left' },
    { label: 'Sinh viên', field: 'name', align: 'left' },
    { label: 'Mã SV', field: 'student_code', align: 'left' },
    { label: 'Lớp', field: 'class', align: 'left' },
    { label: 'Liên hệ', field: 'contact', align: 'left' },
    { label: 'Trạng thái', field: 'status', align: 'left' },
    { label: 'Thao tác', field: 'actions', align: 'center' }
  ];

  // Custom row renderer
  const renderRow = (student, index) => (
    <>
      <TableCell>{index + 1}</TableCell>
      <TableCell>
        <Box display="flex" alignItems="center">
          <Avatar sx={{ mr: 2, bgcolor: 'primary.main' }}>
            <PersonIcon />
          </Avatar>
          <Box>
            <Typography variant="subtitle2" fontWeight="bold">
              {student.name}
            </Typography>
            <Typography variant="caption" color="text.secondary">
              ID: #{student.id}
            </Typography>
          </Box>
        </Box>
      </TableCell>
      <TableCell>
        <Chip label={student.student_code} color="primary" variant="outlined" size="small" />
      </TableCell>
      <TableCell>
        <Typography variant="body2">{student.class || 'Chưa phân lớp'}</Typography>
      </TableCell>
      <TableCell>
        <Box>
          {student.email && (
            <Box display="flex" alignItems="center" mb={0.5}>
              <EmailIcon sx={{ fontSize: 16, mr: 0.5, color: 'text.secondary' }} />
              <Typography variant="caption">{student.email}</Typography>
            </Box>
          )}
          {student.phone && (
            <Box display="flex" alignItems="center">
              <PhoneIcon sx={{ fontSize: 16, mr: 0.5, color: 'text.secondary' }} />
              <Typography variant="caption">{student.phone}</Typography>
            </Box>
          )}
        </Box>
      </TableCell>
      <TableCell>
        <Chip
          label={student.status === 'active' ? 'Hoạt động' : student.status === 'graduated' ? 'Đã tốt nghiệp' : 'Không hoạt động'}
          color={student.status === 'active' ? 'success' : student.status === 'graduated' ? 'info' : 'default'}
          size="small"
        />
      </TableCell>
      <TableCell align="center">
        {isSuperAdmin() ? (
          <ActionButtons
            onEdit={() => handleOpenDialog(student)}
            onDelete={() => handleDelete(student.id, student.name)}
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
            <PersonIcon sx={{ mr: 2, verticalAlign: 'middle' }} />
            Quản Lý Sinh Viên
          </Typography>
          <Typography variant="subtitle1" color="text.secondary">
            Quản lý thông tin sinh viên trong hệ thống
          </Typography>
        </Box>
        {isSuperAdmin() && (
          <Button
            variant="contained"
            color="primary"
            startIcon={<AddIcon />}
            onClick={() => handleOpenDialog()}
          >
            Thêm sinh viên
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
            searchPlaceholder="Tìm kiếm sinh viên..."
          />
        </CardContent>
      </Card>

      {/* Data Table */}
      <Card>
        <CardContent>
          <Box display="flex" justifyContent="space-between" alignItems="center" mb={2}>
            <Typography variant="h6">
              Danh sách sinh viên ({filteredStudents.length} sinh viên)
            </Typography>
          </Box>
          
          <DataTable
            columns={columns}
            data={filteredStudents}
            loading={loading}
            error={error}
            renderRow={renderRow}
            emptyMessage="Không tìm thấy sinh viên nào"
          />
        </CardContent>
      </Card>

      {/* Form Dialog */}
      <FormDialog
        open={openDialog}
        onClose={handleCloseDialog}
        onSubmit={handleSubmit}
        title={editingStudent ? 'Chỉnh sửa sinh viên' : 'Thêm sinh viên mới'}
        submitText={editingStudent ? 'Cập nhật' : 'Tạo mới'}
        PaperProps={dialogPaperProps}
      >
        <FormLayout>
          <FieldRow label="Họ và tên" required>
            <TextField
              fullWidth
              {...fieldInputProps}
              value={formData.name}
              onChange={(e) => setFormData({ ...formData, name: e.target.value })}
            />
          </FieldRow>
          <FieldRow label="Mã sinh viên" required>
            <TextField
              fullWidth
              {...fieldInputProps}
              value={formData.student_code}
              onChange={(e) => setFormData({ ...formData, student_code: e.target.value })}
            />
          </FieldRow>
          <FieldRow label="Lớp">
            <TextField
              fullWidth
              {...fieldInputProps}
              value={formData.class}
              onChange={(e) => setFormData({ ...formData, class: e.target.value })}
            />
          </FieldRow>
          <FieldRow label="Email">
            <TextField
              fullWidth
              {...fieldInputProps}
              type="email"
              value={formData.email}
              onChange={(e) => setFormData({ ...formData, email: e.target.value })}
            />
          </FieldRow>
          <FieldRow label="Số điện thoại">
            <TextField
              fullWidth
              {...fieldInputProps}
              value={formData.phone}
              onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
            />
          </FieldRow>
          <FieldRow label="Địa chỉ">
            <TextField
              fullWidth
              {...fieldInputProps}
              multiline
              rows={2}
              value={formData.address}
              onChange={(e) => setFormData({ ...formData, address: e.target.value })}
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
              <option value="graduated">Đã tốt nghiệp</option>
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

export default Students;

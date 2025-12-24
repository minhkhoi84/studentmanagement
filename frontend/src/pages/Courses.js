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
  Book as BookIcon,
  Person as PersonIcon,
  CreditCard as CreditCardIcon
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

const Courses = () => {
  const { isSuperAdmin } = useAuth();
  const [teachers, setTeachers] = useState([]);
  const [openDialog, setOpenDialog] = useState(false);
  const [editingCourse, setEditingCourse] = useState(null);
  const [snackbar, setSnackbar] = useState({ open: false, message: '', severity: 'success' });
  const [formData, setFormData] = useState({
    name: '',
    code: '',
    description: '',
    credits: '',
    teacher_id: '',
    status: 'active'
  });

  // Use CRUD hook
  const {
    data: courses,
    loading,
    error,
    searchTerm,
    filters,
    handleSearch,
    handleFilter,
    create,
    update,
    remove
  } = useCrud('/courses');

  // Fetch teachers
  useEffect(() => {
    const fetchTeachers = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/teachers');
        if (!response.ok) throw new Error('Failed to fetch teachers');
        const data = await response.json();
        setTeachers(data.data || data || []);
      } catch (err) {
        console.error('Error fetching teachers:', err);
      }
    };
    fetchTeachers();
  }, []);

  const handleOpenDialog = (courseData = null) => {
    if (courseData) {
      setEditingCourse(courseData);
      setFormData({
        name: courseData.name || '',
        code: courseData.code || '',
        description: courseData.description || '',
        credits: courseData.credits || '',
        teacher_id: courseData.teacher_id || '',
        status: courseData.status || 'active'
      });
    } else {
      setEditingCourse(null);
      setFormData({
        name: '',
        code: '',
        description: '',
        credits: '',
        teacher_id: '',
        status: 'active'
      });
    }
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setOpenDialog(false);
    setEditingCourse(null);
    setFormData({
      name: '',
      code: '',
      description: '',
      credits: '',
      teacher_id: '',
      status: 'active'
    });
  };

  const handleSubmit = async () => {
    try {
      if (editingCourse) {
        await update(editingCourse.id, formData);
        setSnackbar({
          open: true,
          message: 'Môn học đã được cập nhật thành công!',
          severity: 'success'
        });
      } else {
        await create(formData);
        setSnackbar({
          open: true,
          message: 'Môn học đã được tạo thành công!',
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
    if (window.confirm(`Bạn có chắc chắn muốn xóa môn học "${name}"?`)) {
      try {
        await remove(id);
        setSnackbar({
          open: true,
          message: 'Môn học đã được xóa thành công!',
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
  const filteredCourses = courses.filter(course => {
    const matchesStatus = !filters.status || course.status === filters.status;
    const matchesTeacher = !filters.teacher_id || course.teacher_id === parseInt(filters.teacher_id);
    return matchesStatus && matchesTeacher;
  });

  // Filter options
  const filterOptions = [
    {
      key: 'teacher_id',
      label: 'Giảng viên',
      options: teachers.map(teacher => ({ value: teacher.id, label: teacher.name }))
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

  // Table columns
  const columns = [
    { label: '#', field: 'index', align: 'left' },
    { label: 'Mã môn học', field: 'code', align: 'left' },
    { label: 'Tên môn học', field: 'name', align: 'left' },
    { label: 'Giảng viên', field: 'teacher', align: 'left' },
    { label: 'Tín chỉ', field: 'credits', align: 'center' },
    { label: 'Trạng thái', field: 'status', align: 'left' },
    { label: 'Thao tác', field: 'actions', align: 'center' }
  ];

  // Custom row renderer
  const renderRow = (course, index) => (
    <>
      <TableCell>{index + 1}</TableCell>
      <TableCell>
        <Chip label={course.code} color="primary" variant="outlined" size="small" />
      </TableCell>
      <TableCell>
        <Box display="flex" alignItems="center">
          <BookIcon sx={{ mr: 1, color: 'primary.main' }} />
          <Box>
            <Typography variant="subtitle2" fontWeight="bold">
              {course.name}
            </Typography>
            {course.description && (
              <Typography variant="caption" color="text.secondary" noWrap>
                {course.description.substring(0, 50)}
                {course.description.length > 50 ? '...' : ''}
              </Typography>
            )}
          </Box>
        </Box>
      </TableCell>
      <TableCell>
        {course.teacher ? (
          <Box display="flex" alignItems="center">
            <PersonIcon sx={{ mr: 1, color: 'secondary.main', fontSize: 20 }} />
            <Typography variant="body2">{course.teacher.name}</Typography>
          </Box>
        ) : (
          <Typography variant="body2" color="text.secondary">
            Chưa phân công
          </Typography>
        )}
      </TableCell>
      <TableCell align="center">
        <Chip
          icon={<CreditCardIcon />}
          label={`${course.credits} TC`}
          color="secondary"
          size="small"
        />
      </TableCell>
      <TableCell>
        <Chip
          label={course.status === 'active' ? 'Hoạt động' : 'Không hoạt động'}
          color={course.status === 'active' ? 'success' : 'default'}
          size="small"
        />
      </TableCell>
      <TableCell align="center">
        {isSuperAdmin() ? (
          <ActionButtons
            onEdit={() => handleOpenDialog(course)}
            onDelete={() => handleDelete(course.id, course.name)}
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
            <BookIcon sx={{ mr: 2, verticalAlign: 'middle' }} />
            Quản Lý Môn Học
          </Typography>
          <Typography variant="subtitle1" color="text.secondary">
            Quản lý thông tin các môn học trong hệ thống
          </Typography>
        </Box>
        {isSuperAdmin() && (
          <Button
            variant="contained"
            color="primary"
            startIcon={<AddIcon />}
            onClick={() => handleOpenDialog()}
          >
            Thêm môn học
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
            searchPlaceholder="Tìm kiếm môn học..."
          />
        </CardContent>
      </Card>

      {/* Data Table */}
      <Card>
        <CardContent>
          <Box display="flex" justifyContent="space-between" alignItems="center" mb={2}>
            <Typography variant="h6">
              Danh sách môn học ({filteredCourses.length} môn)
            </Typography>
          </Box>
          
          <DataTable
            columns={columns}
            data={filteredCourses}
            loading={loading}
            error={error}
            renderRow={renderRow}
            emptyMessage="Không tìm thấy môn học nào"
          />
        </CardContent>
      </Card>

      {/* Form Dialog */}
      <FormDialog
        open={openDialog}
        onClose={handleCloseDialog}
        onSubmit={handleSubmit}
        title={editingCourse ? 'Chỉnh sửa môn học' : 'Thêm môn học mới'}
        submitText={editingCourse ? 'Cập nhật' : 'Tạo mới'}
      >
        <Grid container spacing={2}>
          <Grid item xs={12}>
            <TextField
              fullWidth
              label="Tên môn học"
              required
              value={formData.name}
              onChange={(e) => setFormData({ ...formData, name: e.target.value })}
            />
          </Grid>
          <Grid item xs={12} sm={6}>
            <TextField
              fullWidth
              label="Mã môn học"
              required
              value={formData.code}
              onChange={(e) => setFormData({ ...formData, code: e.target.value })}
            />
          </Grid>
          <Grid item xs={12} sm={6}>
            <TextField
              fullWidth
              type="number"
              label="Số tín chỉ"
              required
              value={formData.credits}
              onChange={(e) => setFormData({ ...formData, credits: e.target.value })}
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
              label="Giảng viên"
              value={formData.teacher_id}
              onChange={(e) => setFormData({ ...formData, teacher_id: e.target.value })}
            >
              <option value="">Chọn giảng viên</option>
              {teachers.map((teacher) => (
                <option key={teacher.id} value={teacher.id}>
                  {teacher.name}
                </option>
              ))}
            </TextField>
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

export default Courses;
